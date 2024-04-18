<?php

namespace App\Controller;

use App\Entity\SignalementCommentaire;
use App\Form\SignalementCommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\SignalementCommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/signalement/commentaire")
 */
class SignalementCommentaireController extends AbstractController
{
    /**
     * @Route("/", name="signalement_commentaire_index", methods={"GET"})
     */
    public function indexSignalementCommentaire(SignalementCommentaireRepository $signalementCommentaireRepository): Response
    {
        return $this->render('signalement_commentaire/index.html.twig', [
            'signalement_commentaires' => $signalementCommentaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="signalement_commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $signalementCommentaire = new SignalementCommentaire();
        $form = $this->createForm(SignalementCommentaireType::class, $signalementCommentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($signalementCommentaire);
            $entityManager->flush();

            return $this->redirectToRoute('signalement_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement_commentaire/new.html.twig', [
            'signalement_commentaire' => $signalementCommentaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_commentaire_show", methods={"GET"})
     */
    public function show(SignalementCommentaire $signalementCommentaire, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = $commentaireRepository->find($signalementCommentaire->getCommentaire());

        return $this->render('signalement_commentaire/show.html.twig', [
            'commentaire' => $commentaire,
            'signalement_commentaire' => $signalementCommentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="signalement_commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SignalementCommentaire $signalementCommentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalementCommentaireType::class, $signalementCommentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('signalement_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement_commentaire/edit.html.twig', [
            'signalement_commentaire' => $signalementCommentaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="signalement_commentaire_delete", methods={"POST"})
     */
    public function delete(Request $request, SignalementCommentaire $signalementCommentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $signalementCommentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($signalementCommentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('signalement_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
