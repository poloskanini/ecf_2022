<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Repository\UserRepository;
use App\Repository\PartnerRepository;
use App\Repository\StructureRepository;
use App\Repository\PartenaireRepository;
use App\Repository\PermissionsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'home')]
    #[IsGranted('ROLE_USER')]
    public function index(UserRepository $userRepository, PartnerRepository $partnerRepository, StructureRepository $structureRepository, PermissionsRepository $permissionsRepository): Response
    {

        $mail = new Mail();
        $mail->send('nicolasbarthes.lana@gmail.com', 'John Doe', 'Mon premier mail', 'Bonjour John et bienvenue chez STUDI FITNESS');

        return $this->render('home/index.html.twig', [
            'users' => $userRepository->findAll(),
            'partners' => $partnerRepository->findAll(),
            'structures' => $structureRepository->findAll(),
            'permissions' => $permissionsRepository->findAll(),

        ]);
    }
}