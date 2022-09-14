<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    public function __construct(
        public EntityManagerInterface $entityManager
        ) {}

    #[Route('/reset/password', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
           return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            
            if($user) {
                // 1 : Enregistrer en base la demande de reset_password avec user, token, createdAt.
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // 2 : Envoyer un email à l'utilisateur avec un lien lui permettant de mettre à jour son mot de passe
                $url = $this->generateUrl('app_update_password', [
                    'token' => $reset_password->getToken()
                    ]);

                $content = "Bonjour ".$user->getName()."<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site STUDI FITNESS.<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='https://sfg.nicolasbarthes.com".$url."'> mettre à jour votre mot de passe.";

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getName(), 'Réinitialiser votre mot de passe STUDI FITNESS', $content );

                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail avec la procédure de réinitialisation de mot de passe.');
            } else {
                $this->addFlash('notice', 'Cette adresse e-mail est inconnue.');
            }
        }


        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/update-password/{token}', name: 'app_update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $passwordHasher): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password)
        {
            return $this->redirectToRoute('app_reset_password');
        }

        // Vérifier si le createdAt = now - 3h
        $now = new DateTimeImmutable();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            // Token expiré
            $this->addFlash('notice', 'Votre demande de réinitialisation de mot de passe a expirée. Merci de la renouveler.');
            return $this->redirectToRoute('app_reset_password');
        }

        // Rendre une vue avec mot de passe et confirmez votre mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();

            // Encodage des mots de passe
            $password = $passwordHasher->hashPassword($reset_password->getUser(), $new_pwd);
            // Je réinjecte $password qui est crypté dans l'objet User()
            $reset_password->getUser()->setPassword($password);
            // Flush en base de données
            $this->entityManager->flush();
            // Redirection de l'utilisateur vers la page de connexion
            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('app_login');
        };

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
