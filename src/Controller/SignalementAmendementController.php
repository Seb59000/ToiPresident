<?php

namespace App\Controller;

use App\Entity\SignalementAmendement;
use App\Form\SignalementAmendementType;
use App\Repository\AmendementRepository;
use App\Repository\SignalementAmendementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/signalement/amendement")
 */
class SignalementAmendementController extends AbstractController
{
    /**
     * @Route("/", name="signalement_amendement_index", methods={"GET"})
     */
    public function indexSignalementAmendement(SignalementAmendementRepository $signalementAmendementRepository): Response
    {
        return $this->render('signalement_amendement/index.html.twig', [
            'signalement_amendements' => $signalementAmendementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="signalement_amendement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $signalementAmendement = new SignalementAmendement();
        $form = $this->createForm(SignalementAmendementType::class, $signalementAmendement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($signalementAmendement);
            $entityManager->flush();

            return $this->redirectToRoute('signalement_amendement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement_amendement/new.html.twig', [
            'signalement_amendement' => $signalementAmendement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_amendement_show", methods={"GET"})
     */
    public function show(SignalementAmendement $signalementAmendement, AmendementRepository $amendementRepository): Response
    {
        $amendement = $amendementRepository->find($signalementAmendement->getAmendement());
        return $this->render('signalement_amendement/show.html.twig', [
            'amendement' => $amendement,
            'signalement_amendement' => $signalementAmendement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="signalement_amendement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SignalementAmendement $signalementAmendement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalementAmendementType::class, $signalementAmendement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('signalement_amendement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement_amendement/edit.html.twig', [
            'signalement_amendement' => $signalementAmendement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_amendement_delete", methods={"POST"})
     */
    public function delete(Request $request, SignalementAmendement $signalementAmendement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $signalementAmendement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($signalementAmendement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('signalement_amendement_index', [], Response::HTTP_SEE_OTHER);
    }
}
