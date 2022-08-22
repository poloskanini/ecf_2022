<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Partner;
use App\Form\PartnerType;
use App\Form\CreatePartnerType;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use App\Repository\PartnerRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/partner')]
class PartnerController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/', name: 'app_partner_index', methods: ['GET'])]
    public function index(PartnerRepository $partnerRepository): Response
    {
        return $this->render('partner/index.html.twig', [
            'partners' => $partnerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_partner_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, PartnerRepository $partnerRepository, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User(); // J'instancie ma classe User()
        $partner = new Partner(); // J'instancie ma classe Partner()
        
        $form = $this->createForm(PartnerType::class, $user); // Mon formulaire PartnerType

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
             'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_partner_edit', methods: ['GET', 'POST'])]
    #[Entity('partner', options: ['id' => 'partner_id'])]
    public function edit(Request $request, Partner $partner, UserRepository $userRepository, PartnerRepository $partnerRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(PartnerType::class, $partner); // Mon formulaire PartnerType

        

        return $this->renderForm('partner/_edit.html.twig', [
             'form' => $form,
        ]);
    }

}
