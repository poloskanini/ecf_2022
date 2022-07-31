<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Form\PartenaireType;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partenaire')]
class PartenaireController extends AbstractController
{
    #[Route('/', name: 'app_partenaire_index', methods: ['GET'])]
    public function index(PartenaireRepository $partenaireRepository): Response
    {
        return $this->render('partenaire/index.html.twig', [
            'partenaires' => $partenaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_partenaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PartenaireRepository $partenaireRepository): Response
    {
        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partenaireRepository->add($partenaire, true);
    
            $this->addFlash(
                'success',
                'Votre partenaire a été correctement ajouté'
                );

            return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partenaire/new.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_partenaire_show', methods: ['GET'])]
    public function show(Partenaire $partenaire): Response
    {
        return $this->render('partenaire/show.html.twig', [
            'partenaire' => $partenaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_partenaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partenaire $partenaire, PartenaireRepository $partenaireRepository): Response
    {
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partenaireRepository->add($partenaire, true);

            $this->addFlash(
                'success',
                'Votre partenaire a été correctement modifié'
                );

            return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partenaire/edit.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_partenaire_delete', methods: ['GET'])]
    // public function delete(Request $request, Partenaire $partenaire, PartenaireRepository $partenaireRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$partenaire->getId(), $request->request->get('_token'))) {
    //         $partenaireRepository->remove($partenaire, true);
    //     }

    //     return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    // }
    public function delete(EntityManagerInterface $manager, Partenaire $partenaire) {
        
        if(!$partenaire) {
            $this->addFlash(
                'warning',
                'Le partenaire n\'a pas été trouvé'
                );

                return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $manager->remove($partenaire);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre partenaire a été supprimé avec succès'
            );

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
