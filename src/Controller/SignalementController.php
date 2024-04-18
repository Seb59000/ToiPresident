<?php

namespace App\Controller;

use App\Entity\Amendement;
use App\Entity\ComAmendement;
use App\Entity\Commentaire;
use App\Entity\Petition;
use App\Entity\SignalementAmendement;
use App\Entity\SignalementComAmendement;
use App\Entity\SignalementCommentaire;
use App\Entity\SignalementPetition;
use App\Entity\SignalementSujet;
use App\Entity\Sujet;
use App\Form\SignalementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignalementController extends AbstractController
{
    /**
     * @Route("/espaceperso/signalement/new/{id}", name="petition_signalement_new", methods={"GET", "POST"})
     */
    public function newPetition(Request $request, EntityManagerInterface $entityManager, Petition $petition): Response
    {
        $signalement = new SignalementPetition();
        $form = $this->createForm(SignalementType::class, $signalement);
        $form->handleRequest($request);

        $titre = $petition->getTitre();
        $contenu = $petition->getContenu();

        if ($form->isSubmitted() && $form->isValid()) {
            $signalement->setUser($this->getUser());
            $signalement->setPetition($petition);
            $entityManager->persist($signalement);
            $entityManager->flush();

            return $this->render('signalement/ok.html.twig');
        }

        return $this->renderForm('signalement/new.html.twig', [
            'titre' => $titre,
            'contenu' => $contenu,
            'signalement' => $signalement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/espaceperso/signalement/amendement/new/{id}", name="amendement_signalement_new", methods={"GET", "POST"})
     */
    public function newAmendement(Request $request, EntityManagerInterface $entityManager, Amendement $amendement): Response
    {
        $signalement = new SignalementAmendement();
        $form = $this->createForm(SignalementType::class, $signalement);
        $form->handleRequest($request);
        $titre = $amendement->getTitre();
        $contenu = $amendement->getContenu();

        if ($form->isSubmitted() && $form->isValid()) {
            $signalement->setUser($this->getUser());
            $signalement->setAmendement($amendement);
            $entityManager->persist($signalement);
            $entityManager->flush();

            return $this->render('signalement/ok.html.twig');
        }

        return $this->renderForm('signalement/new.html.twig', [
            'titre' => $titre,
            'contenu' => $contenu,
            'signalement' => $signalement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/espaceperso/signalement/com/amendement/new/{id}", name="com_amendement_signalement_new", methods={"GET", "POST"})
     */
    public function newComAmendement(Request $request, EntityManagerInterface $entityManager, ComAmendement $comAmendement): Response
    {
        $signalement = new SignalementComAmendement();
        $form = $this->createForm(SignalementType::class, $signalement);
        $form->handleRequest($request);
        $contenu = $comAmendement->getContenu();

        if ($form->isSubmitted() && $form->isValid()) {
            $signalement->setUser($this->getUser());
            $signalement->setComAmendement($comAmendement);
            $entityManager->persist($signalement);
            $entityManager->flush();

            return $this->render('signalement/ok.html.twig');
        }

        return $this->renderForm('signalement/newcom.html.twig', [
            'contenu' => $contenu,
            'signalement' => $signalement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/espaceperso/signalement/sujet/new/{id}", name="sujet_signalement_new", methods={"GET", "POST"})
     */
    public function newSujet(Request $request, EntityManagerInterface $entityManager, Sujet $sujet): Response
    {
        $signalement = new SignalementSujet();
        $form = $this->createForm(SignalementType::class, $signalement);
        $form->handleRequest($request);

        $titre = $sujet->getTitre();
        $contenu = $sujet->getContenu();

        if ($form->isSubmitted() && $form->isValid()) {
            $signalement->setUser($this->getUser());
            $signalement->setSujet($sujet);
            $entityManager->persist($signalement);
            $entityManager->flush();

            return $this->render('signalement/ok.html.twig');
        }

        return $this->renderForm('signalement/new.html.twig', [
            'titre' => $titre,
            'contenu' => $contenu,
            'signalement' => $signalement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/espaceperso/signalement/commentaire/new/{id}", name="commentaire_signalement_new", methods={"GET", "POST"})
     */
    public function newCommentaire(Request $request, EntityManagerInterface $entityManager, Commentaire $commentaire): Response
    {
        $signalement = new SignalementCommentaire();
        $form = $this->createForm(SignalementType::class, $signalement);
        $form->handleRequest($request);
        $contenu = $commentaire->getContenu();

        if ($form->isSubmitted() && $form->isValid()) {
            $signalement->setUser($this->getUser());
            $signalement->setCommentaire($commentaire);
            $entityManager->persist($signalement);
            $entityManager->flush();

            return $this->render('signalement/ok.html.twig');
        }

        return $this->renderForm('signalement/newcom.html.twig', [
            'contenu' => $contenu,
            'signalement' => $signalement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/signalement/{id}", name="signalement_show", methods={"GET"})
     */
    /*
    public function show(Signalement $signalement): Response
    {
        return $this->render('signalement/show.html.twig', [
            'signalement' => $signalement,
        ]);
    }
*/

    /**
     * @Route("/signalement/{id}/edit", name="signalement_edit", methods={"GET", "POST"})
     */

    /*
    public function edit(Request $request, Signalement $signalement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalementType::class, $signalement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('signalement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signalement/edit.html.twig', [
            'signalement' => $signalement,
            'form' => $form,
        ]);
    }
*/

    /**
     * @Route("/signalement/{id}", name="signalement_delete", methods={"POST"})
     */

    /*
    public function delete(Request $request, Signalement $signalement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $signalement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($signalement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('signalement_index', [], Response::HTTP_SEE_OTHER);
    }
    */
}
