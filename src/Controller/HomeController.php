<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    // Méthode pour récupérer les données de la DB et les insérer dans les cards
}