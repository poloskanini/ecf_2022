<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Partner;
use App\Entity\Structure;
use App\Form\StructureType;
use App\Repository\UserRepository;
use App\Repository\PartnerRepository;
use App\Repository\StructureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/structure')]
class StructureController extends AbstractController
{
    #[Route('/', name: 'app_structure_index', methods: ['GET'])]
    public function index(StructureRepository $structureRepository): Response
    {
        return $this->render('structure/index.html.twig', [
            'structures' => $structureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_structure_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, StructureRepository $structureRepository, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User(); // J'instancie ma classe User()
        $structure = new Structure(); // J'instancie ma classe User()
        
        $form = $this->createForm(StructureType::class); // Mon formulaire UserType

        $form->handleRequest($request); // Écoute la requête entrante

        if ($form->isSubmitted() && $form->isValid()) {
            // Injecte dans mon objet User() toutes les données qui sont récupérées du formulaire
            $user = $form->getData();
            
            // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            // Je réinjecte $password qui est crypté dans l'objet User()
            $user->setPassword($password);

            // Je récupère les données non mappées de postalAdress et les injecte dans le setPostalAdress de ma structure.
            $structure->setPostalAdress($form->get('postalAdress')->getData());
            // Je définis que la nouvelle donnée aura par défaut le ['ROLE_STRUCTURE]
            $user->setRoles(['ROLE_STRUCTURE']);

            // dump($form->get('id')->getData());

            // Je récupère les données non mappées des booléens de mon partner (champ 'id' en ligne 97 du StructureType) et les injecte dans ma structure.
            // Les booléens du Partner deviennent les booléens de la structure :)
            $structure->setIsPlanning($form->get('id')->getData()->isIsPlanning());
            $structure->setIsNewsletter($form->get('id')->getData()->isIsNewsletter());
            $structure->setIsBoissons($form->get('id')->getData()->isIsBoissons());
            $structure->setIsSms($form->get('id')->getData()->isIsSms());
            $structure->setIsConcours($form->get('id')->getData()->isIsConcours());
            
            //Je définis que la structure de mon User est $structure
            $structure->setUser($user);
            $user->setStructure($structure);

            // Je définis que le partenaire de ma structure est la data que contient "id"
            $structure->setPartner($form->get('id')->getData());

            // dump($user);
            // dump($structure);

            $userRepository->add($user, true);
            $structureRepository->add($structure, true);

            $this->addFlash(
                'success',
                'La structure "' .$user->getName(). '" a été ajouté avec succès'
            );

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('structure/_new.html.twig', [
             'user' => $user,
             'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_structure_show', methods: ['GET'])]
    public function show(Structure $structure, StructureRepository $structureRepository): Response
    {
        $partners = $structure->getPartner();

        return $this->render('structure/_show.html.twig', [
            'partners' => $partners,
            'structure' => $structure
        ]);
    }


    #[Route('/edit/{id}', name: 'app_structure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, StructureRepository $structureRepository, UserRepository $userRepository): Response
    {
        $form = $this->createForm(StructureType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $structureRepository->add($structure, true);

            return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('structure/_edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_structure_delete', methods: ['POST'])]
    public function delete(Request $request, Structure $structure, StructureRepository $structureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$structure->getId(), $request->request->get('_token'))) {
            $structureRepository->remove($structure, true);
        }

        return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
    }
}
