<?php

namespace App\Controller;

use App\Entity\ComAmendement;
use App\Entity\SignalementComAmendement;
use App\Form\SignalementComAmendementType;
use App\Repository\ComAmendementRepository;
use App\Repository\SignalementComAmendementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/signalement/com/amendement")
 */
class SignalementComAmendementController extends AbstractController
{
    /**
     * @Route("/", name="signalement_com_amendement_index", methods={"GET"})
     */
    public function indexSignalementComAmendement(SignalementComAmendementRepository $signalementComAmendementRepository): Response
    {
        return $this->render('signalement_com_amendement/index.html.twig', [
            'signalement_com_amendements' => $signalementComAmendementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="signalement_com_amendement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $signalementComAmendement = new SignalementComAmendement();
        $form = $this->createForm(SignalementComAmendementType::class, $signalementComAmendement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($signalementComAmendement);
            $entityManager->flush();

            return $this->redirectToRoute('signalement_com_amendement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement_com_amendement/new.html.twig', [
            'signalement_com_amendement' => $signalementComAmendement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_com_amendement_show", methods={"GET"})
     */
    public function show(SignalementComAmendement $signalementComAmendement, ComAmendementRepository $comAmendementRepository): Response
    {
        $commentaire = $comAmendementRepository->find($signalementComAmendement->getComAmendement());

        return $this->render('signalement_com_amendement/show.html.twig', [
            'com_amendement' => $commentaire,
            'signalement_com_amendement' => $signalementComAmendement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="signalement_com_amendement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SignalementComAmendement $signalementComAmendement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalementComAmendementType::class, $signalementComAmendement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('signalement_com_amendement/edit.html.twig', [
            'signalement_com_amendement' => $signalementComAmendement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_com_amendement_delete", methods={"POST"})
     */
    public function delete(Request $request, SignalementComAmendement $signalementComAmendement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $signalementComAmendement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($signalementComAmendement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('signalement_com_amendement_index', [], Response::HTTP_SEE_OTHER);
    }
}
