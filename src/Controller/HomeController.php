<?php

namespace App\Controller;

use App\Repository\PartenaireRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'home')]
    public function index(UserRepository $userRepository, PartenaireRepository $partenaireRepository
    ): Response
    {
        return $this->render('home/index.html.twig', [
            'users' => $userRepository->findAll(),
            'partners' => $partenaireRepository->findAll(),
        ]);
    }
}