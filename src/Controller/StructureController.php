<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Partner;
use App\Entity\Structure;
use App\Form\UserShowType;
use App\Form\StructureType;
use App\Form\StructureFormType;
use App\Repository\UserRepository;
use App\Form\StructureFormShowType;
use App\Repository\PartnerRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Permissions;
use App\Repository\PermissionsRepository;

#[Route('/structure')]
#[IsGranted('ROLE_ADMIN')]
class StructureController extends AbstractController
{

    // INDEX FOR ALL STRUCTURES IN DB
    #[Route('/', name: 'app_structure_index', methods: ['GET'])]
    public function index(StructureRepository $structureRepository, PermissionsRepository $permissionsRepository): Response
    {
        return $this->render('structure/index.html.twig', [
            'structures' => $structureRepository->findAll(),
            'permissions' => $permissionsRepository->findAll(),

        ]);
    }

    // CREATE A NEW STRUCTURE
    #[Route('/new', name: 'app_structure_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, StructureRepository $structureRepository, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User(); // J'instancie ma classe User()
        $structure = new Structure(); // J'instancie ma classe User()
        $permissions = new Permissions();
        
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
                'La structure "' .$user->getName(). '" a été ajoutée avec succès. Elle est rattachée au partenaire "' .$structure->getPartner(). '".'
            );

            return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('structure/_new.html.twig', [
             'user' => $user,
             'form' => $form,
        ]);
    }

    // EDIT A STRUCTURE
    #[Route('/edit/{id}', name: 'app_structure_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, UserRepository $userRepository,  StructureRepository $structureRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $structure = $structureRepository->findOneBy(['id' => $id]); // Catch le partner qui a l'id ciblée
        $structureUser = $structure->getUser(); // Catch l'utilisateur relié à ce partner
        $items = ['user' => $structureUser, 'structure' => $structure]; // Tableau regroupant les 2 entités

        $form = $this->createFormBuilder($items) // Formulaire regroupant les 2 entités
            ->add('user', UserType::class, [
                'isEdit' => true,
            ])
            ->add('structure', StructureFormType::class)
            // ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
                $password = $passwordHasher->hashPassword($structureUser, $structureUser->getPassword());
                $structureUser->setPassword($password);
    
                $userRepository->add($structureUser, true);
                $structureRepository->add($structure, true);
    
                $this->addFlash(
                    'success',
                    'L\'utilisateur "' .$structureUser->getName(). '" a été modifié avec succès'
                );
    
                return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
            }

        return $this->renderForm('structure/_edit.html.twig', [
            'structure' => $structure,
            'form' => $form,
        ]);

    }

    // SHOW A STRUCTURE
    #[Route('/show/{id}', name: 'app_structure_show', methods: ['GET'])]
    public function show(int $id, StructureRepository $structureRepository): Response
    {
        $structure = $structureRepository->findOneBy(['id' => $id]);
        $structureUser = $structure->getUser();
        $items = ['user' => $structureUser, 'structure' => $structure];

        $form = $this->createFormBuilder($items)
            ->add('user', UserShowType::class)
            ->add('structure', StructureFormShowType::class)
            // ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->getForm();

            // $form->handleRequest($request);

        return $this->renderForm('structure/_show.html.twig', [
            'structure' => $structure,
            'form' => $form,
        ]);
    }

    // DELETE A STRUCTURE
    #[Route('/{id}/delete', name: 'app_structure_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Structure $structure) {
        
        if(!$structure) {
            $this->addFlash(
                'warning',
                'L\utilisateur n\'a pas été trouvé'
                );
                return $this->render('user/_delete.html.twig', [
                    'structure' => $structure,
                ]);
            }
            
            $manager->remove($structure); //REMOVE
            $manager->flush();
            
            $this->addFlash(
                'danger',
                'L\'utilisateur "' .$structure->getPostalAdress(). '" a été supprimé avec succès'
        );

        return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
    }
}
