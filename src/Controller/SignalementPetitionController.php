<?php

namespace App\Controller;

use App\Entity\SignalementPetition;
use App\Form\SignalementPetitionType;
use App\Repository\PetitionRepository;
use App\Repository\SignalementPetitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/signalement/petition")
 */
class SignalementPetitionController extends AbstractController
{
    /**
     * @Route("/", name="signalement_petition_index", methods={"GET", "POST"})
     */
    public function indexSignalementPetition(SignalementPetitionRepository $signalementPetitionRepository): Response
    {
        return $this->render('signalement_petition/index.html.twig', [
            'signalement_petitions' => $signalementPetitionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="signalement_petition_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $signalementPetition = new SignalementPetition();
        $form = $this->createForm(SignalementPetitionType::class, $signalementPetition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($signalementPetition);
            $entityManager->flush();

            return $this->redirectToRoute('signalement_petition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement_petition/new.html.twig', [
            'signalement_petition' => $signalementPetition,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_petition_show", methods={"GET"})
     */
    public function show(SignalementPetition $signalementPetition, PetitionRepository $petitionRepository): Response
    {
        $petition = $petitionRepository->find($signalementPetition->getPetition());
        return $this->render('signalement_petition/show.html.twig', [
            'petition' => $petition,
            'signalement_petition' => $signalementPetition,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="signalement_petition_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SignalementPetition $signalementPetition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalementPetitionType::class, $signalementPetition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('signalement_petition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement_petition/edit.html.twig', [
            'signalement_petition' => $signalementPetition,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_petition_delete", methods={"POST"})
     */
    public function delete(Request $request, SignalementPetition $signalementPetition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $signalementPetition->getId(), $request->request->get('_token'))) {
            $entityManager->remove($signalementPetition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('signalement_petition_index', [], Response::HTTP_SEE_OTHER);
    }
}
