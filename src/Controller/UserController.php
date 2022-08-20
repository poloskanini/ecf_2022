<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Partner;
use App\Form\UserShowType;
use App\Repository\UserRepository;
use App\Repository\PartnerRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/utilisateurs')]
class UserController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_user_index' ,methods: ['GET'])]
    public function index(UserRepository $userRepository, PartnerRepository $partnerRepository, StructureRepository $structureRepository): Response
    {
        
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'partners' => $partnerRepository->findAll(),
            'structures' => $structureRepository->findAll(),
        ]);
    }

    


    // #[Route('/{id}/show', name: 'app_user_show', methods: ['GET'])]
    // public function show(User $user): Response
    // {
    //     $form = $this->createForm(UserShowType::class, $user);
    //     return $this->renderForm('user/_show.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, User $user, Partner $partner, UserRepository $userRepository, PartnerRepository $partnerRepository, UserPasswordHasherInterface $passwordHasher): Response
    // {
    //     $form = $this->createForm(UserType::class, $user); // Mon formulaire UserType

    //     $form->handleRequest($request); // Écoute la requête entrante

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Injecte dans mon objet User() toutes les données qui sont récupérées du formulaire
    //         $user = $form->getData();
            
    //         // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
    //         $password = $passwordHasher->hashPassword($user, $user->getPassword());
    //         // Je réinjecte $password qui est crypté dans l'objet User()
    //         $user->setPassword($password);

    //         // Je définis que le partenaire de mon User est $partner
    //         $user->setPartner($partner);
    //         $partner->setUser($user);

    //         // Je récupère les données "non mappée" du formulaire UserType et les injecte dans mon instance de Partner.
    //         $partner->setName($form->get('partnerName')->getData());

    //         // Je définis que la nouvelle donnée aura pas défaut le ['ROLE_PARTENAIRE]
    //         $user->setRoles(['ROLE_PARTENAIRE']);

    //         $partner->setIsPlanning($form->get('isPlanning')->getData());
    //         $partner->setIsNewsletter($form->get('isNewsletter')->getData());
    //         $partner->setIsBoissons($form->get('isBoissons')->getData());
    //         $partner->setIsSms($form->get('isSms')->getData());
    //         $partner->setIsConcours($form->get('isConcours')->getData());

    //         $userRepository->add($user, true);
    //         $partnerRepository->add($partner, true);

    //         $this->addFlash(
    //             'success',
    //             'L\'utilisateur "' .$user->getName(). '" a été ajouté avec succès'
    //         );

    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('partner/_edit.html.twig', [
    //          'user' => $user,
    //          'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    // {
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user = $form->getData();
            
    //         $password = $passwordHasher->hashPassword($user, $user->getPassword());
    //         $user->setPassword($password);
    //         $userRepository->add($user, true); // ADD

    //         $this->addFlash(
    //             'success',
    //             'L\'utilisateur "' .$user->getName(). '" a été modifié avec succès'
    //         );

    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('user/_edit.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}/delete', name: 'app_user_delete', methods: ['GET'])]
    // public function delete(EntityManagerInterface $manager, User $user) {
        
    //     if(!$user) {
    //         $this->addFlash(
    //             'warning',
    //             'L\utilisateur n\'a pas été trouvé'
    //             );
    //             return $this->render('user/_delete.html.twig', [
    //                 'user' => $user,
    //             ]);
    //         }
            
    //         $manager->remove($user); //REMOVE
    //         $manager->flush();
            
    //         $this->addFlash(
    //             'danger',
    //             'L\'utilisateur "' .$user->getName(). '" a été supprimé avec succès'
    //     );

    //     return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    // }

}
