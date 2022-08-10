<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
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
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, UserRepository $userRepository): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $userRepository->add($user, true);
    
    //         $this->addFlash(
    //             'success',
    //             'L\'utilisateur "' .$user->getName(). '" a été ajouté avec succès'
    //         );

    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('user/_new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User(); // J'instancie ma classe User(), j'ai donc un nouvel objet User
        $form = $this->createForm(UserType::class, $user); // J'instancie mon formulaire avec la méthode createForm... (en paramètres : la classe du formulaire, et l'objet User )

        $form->handleRequest($request); // Écoute la requête entrante

        if ($form->isSubmitted() && $form->isValid()) {
            // Injecte dans mon objet User() toutes les données qui sont récupérées du formulaire
            $user = $form->getData();
            
            // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            // Je réinjecte $password qui est crypté dans l'objet User()
            $user->setPassword($password);

            $userRepository->add($user, true);

            $this->addFlash(
                'success',
                'L\'utilisateur "' .$user->getName(). '" a été ajouté avec succès'
            );

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_new.html.twig', [
             'user' => $user,
             'form' => $form,
        ]);
    }


    #[Route('/{id}/show', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            $this->addFlash(
                'success',
                'L\'utilisateur "' .$user->getName(). '" a été modifié avec succès'
            );

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}/delete', name: 'app_user_delete', methods: ['GET'])]
    // public function delete(EntityManagerInterface $manager, User $user) {
        
    //     if(!$user) {
    //         $this->addFlash(
    //             'warning',
    //             'L\utilisateur n\'a pas été trouvé'
    //             );

    //             return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }
        
    //     $manager->remove($user);
    //     $manager->flush();

    //     $this->addFlash(
    //         'danger',
    //         'L\'utilisateur "' .$user->getName(). '" a été supprimé avec succès'
    //     );

    //     return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    // }

    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['GET'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->remove($user, true);

            $this->addFlash(
                'success',
                'L\'utilisateur "' .$user->getName(). '" a été supprimé avec succès'
            );

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_delete.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}