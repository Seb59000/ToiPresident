<?php

namespace App\Controller;

use App\Entity\SignalementSujet;
use App\Form\SignalementSujetType;
use App\Repository\SignalementSujetRepository;
use App\Repository\SujetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/signalement/sujet")
 */
class SignalementSujetController extends AbstractController
{
    /**
     * @Route("/signalement/sujet", name="signalement_sujet_index", methods={"GET"})
     */
    public function indexSignalementSujet(SignalementSujetRepository $signalementSujetRepository): Response
    {
        return $this->render('signalement_sujet/index.html.twig', [
            'signalement_sujets' => $signalementSujetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="signalement_sujet_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $signalementSujet = new SignalementSujet();
        $form = $this->createForm(SignalementSujetType::class, $signalementSujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($signalementSujet);
            $entityManager->flush();

            return $this->redirectToRoute('signalement_sujet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement_sujet/new.html.twig', [
            'signalement_sujet' => $signalementSujet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_sujet_show", methods={"GET"})
     */
    public function show(SignalementSujet $signalementSujet, SujetRepository $sujetRepository): Response
    {
        $sujet = $sujetRepository->find($signalementSujet->getSujet());

        return $this->render('signalement_sujet/show.html.twig', [
            'sujet' => $sujet,
            'signalement_sujet' => $signalementSujet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="signalement_sujet_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SignalementSujet $signalementSujet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalementSujetType::class, $signalementSujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('signalement_sujet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement_sujet/edit.html.twig', [
            'signalement_sujet' => $signalementSujet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_sujet_delete", methods={"POST"})
     */
    public function delete(Request $request, SignalementSujet $signalementSujet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $signalementSujet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($signalementSujet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('signalement_sujet_index', [], Response::HTTP_SEE_OTHER);
    }
}
