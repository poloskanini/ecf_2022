<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Partner;
use App\Form\PartnerType;
use App\Form\UserShowType;
use App\Form\PartnerFormType;
use App\Form\CreatePartnerType;
use Doctrine\ORM\Mapping\Entity;
use App\Form\PartnerFormShowType;
use App\Repository\UserRepository;
use App\Repository\PartnerRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/partner')]
class PartnerController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    // INDEX FOR ALL PARTNERS IN DB
    #[Route('/', name: 'app_partner_index', methods: ['GET'])]
    public function index(PartnerRepository $partnerRepository): Response
    {
        return $this->render('partner/index.html.twig', [
            'partners' => $partnerRepository->findAll(),
        ]);
    }

    // CREATE A NEW PARTNER
    #[Route('/new', name: 'app_partner_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, PartnerRepository $partnerRepository, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User(); // J'instancie ma classe User()
        $partner = new Partner(); // J'instancie ma classe Partner()
        
        $form = $this->createForm(PartnerType::class); // Mon formulaire PartnerType

        $form->handleRequest($request); // Écoute la requête entrante

        if ($form->isSubmitted() && $form->isValid()) {
            // Injecte dans mon objet User() toutes les données qui sont récupérées du formulaire
            $user = $form->getData();
            
            // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            // Je réinjecte $password qui est crypté dans l'objet User()
            $user->setPassword($password);

            // Je définis que le partenaire de mon User est $partner
            $user->setPartner($partner);
            $partner->setUser($user);

            // Je récupère les données "non mappée" du formulaire UserType et les injecte dans mon instance de Partner.
            $partner->setName($form->get('partnerName')->getData());

            // Je définis que la nouvelle donnée aura pas défaut le ['ROLE_PARTENAIRE]
            $user->setRoles(['ROLE_PARTENAIRE']);

            $partner->setIsPlanning($form->get('isPlanning')->getData());
            $partner->setIsNewsletter($form->get('isNewsletter')->getData());
            $partner->setIsBoissons($form->get('isBoissons')->getData());
            $partner->setIsSms($form->get('isSms')->getData());
            $partner->setIsConcours($form->get('isConcours')->getData());


            $userRepository->add($user, true);
            $partnerRepository->add($partner, true);

            $this->addFlash(
                'success',
                'L\'utilisateur "' .$user->getName(). '" a été ajouté avec succès'
            );

            return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partner/_new.html.twig', [
             'user' => $user,
             'partner' => $partner,
             'form' => $form,
        ]);
    }

    // EDIT A PARTNER
    #[Route('/edit/{id}', name: 'app_partner_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, UserRepository $userRepository, PartnerRepository $partnerRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $partner = $partnerRepository->findOneBy(['id' => $id]); // Catch le partner qui a l'id ciblée
        $partnerUser = $partner->getUser(); // Catch l'utilisateur relié à ce partner
        $items = ['user' => $partnerUser, 'partner' => $partner]; // Tableau regroupant les 2 entités

        $form = $this->createFormBuilder($items) // Formulaire regroupant les 2 entités
            ->add('user', UserType::class)
            ->add('partner', PartnerFormType::class)
            // ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
                $password = $passwordHasher->hashPassword($partnerUser, $partnerUser->getPassword());
                $partnerUser->setPassword($password);
    
                $userRepository->add($partnerUser, true);
                $partnerRepository->add($partner, true);
    
                $this->addFlash(
                    'success',
                    'L\'utilisateur "' .$partnerUser->getName(). '" a été modifié avec succès'
                );
    
                return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
            }

        return $this->renderForm('partner/_edit.html.twig', [
            'partner' => $partner,
            'form' => $form,
        ]);
    }

    // SHOW A PARTNER
    #[Route('/show/{id}', name: 'app_partner_show', methods: ['GET'])]
    public function show(int $id, PartnerRepository $partnerRepository)
    {
        $partner = $partnerRepository->findOneBy(['id' => $id]);
        $partnerUser = $partner->getUser();
        $items = ['user' => $partnerUser, 'partner' => $partner];

        $form = $this->createFormBuilder($items)
            ->add('user', UserShowType::class)
            ->add('partner', PartnerFormShowType::class)
            // ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->getForm();

            // $form->handleRequest($request);

        return $this->renderForm('partner/_show.html.twig', [
            'partner' => $partner,
            'form' => $form,
        ]);
    }
 
    // DELETE A PARTNER
    #[Route('/delete/{id}', name: 'app_partner_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Partner $partner) {
        
        if(!$partner) {
            $this->addFlash(
                'warning',
                'L\utilisateur n\'a pas été trouvé'
                );
                return $this->render('user/_delete.html.twig', [
                    'partner' => $partner,
                ]);
            }
            
            $manager->remove($partner); //REMOVE
            $manager->flush();
            
            $this->addFlash(
                'danger',
                'L\'utilisateur "' .$partner->getName(). '" a été supprimé avec succès'
        );

        return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
    }

}