<?php

namespace App\Controller;

use App\Entity\Sujet;
use App\Entity\Petition;
use App\Entity\SignalementSujet;
use App\Entity\SoutienSujet;
use App\Form\SujetType;
use App\Repository\CommentaireRepository;
use App\Repository\SujetRepository;
use App\Repository\SoutienSujetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SujetController extends AbstractController
{
    /**
     * @Route("/espaceperso/sujet/new/{petition}", name="sujet_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepo, EntityManagerInterface $entityManager, Petition $petition): Response
    {
        $sujet = new Sujet();
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on vérifie qu'il y a un contenu
            $contenu = $form->getData()->getContenu();
            if ($contenu == null) {
                $this->addFlash('alert', 'Veuillez décrire votre commentaire.');
            } else {
                $user = $userRepo->find($this->getUser());
                $sujet->setPseudo($user->getPseudo());
                $sujet->setPetition($petition);

                $entityManager->persist($sujet);
                $entityManager->flush();

                //creation soutien sujet
                $soutien = new SoutienSujet();
                $soutien->setSujet($sujet);
                $soutien->setUser($this->getUser());

                $entityManager->persist($soutien);
                $entityManager->flush();

                $parameters = array(
                    'petition' =>  $petition->getId(),
                    'amendementShow' =>  'false',
                );
                return $this->redirectToRoute('petition_show', $parameters);
            }
        }

        return $this->renderForm('sujet/new.html.twig', [
            'petition' => $petition,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/sujet/{sujet}", name="sujet_show", methods={"GET"})
     */
    public function show(Sujet $sujet, Request $request, CommentaireRepository $commentaireRepo): Response
    {
        // On définit le nombre d'éléments par page
        $limit = 20;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);

        // On récupère le nombre total de commentaires pour le sujet
        $total = $commentaireRepo->getTotalCommentaires($sujet);

        // On récupère les commentaires de la page
        $commentaires = $commentaireRepo->getPaginatedCommentaires($page, $limit, $sujet);

        return $this->renderForm('sujet/show.html.twig', [
            'sujet' => $sujet,
            'petition' => $sujet->getPetition(),
            'commentaires' => $commentaires,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
        ]);
    }

    /**
     * @Route("/admin/sujet/{id}/edit/{signalement}", name="sujet_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sujet $sujet, SignalementSujet $signalement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $entityManager->remove($signalement);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/sujet/{id}", name="sujet_delete", methods={"POST"})
     */
    public function delete(Request $request, Sujet $sujet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sujet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sujet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/espaceperso/sujet/soutien/{sujet}/{petition}", name="sujet_soutien", methods={"POST"})
     */
    public function soutienSujet(Request $request, EntityManagerInterface $entityManager, SoutienSujetRepository $soutienSujetRepo, Sujet $sujet, $petition): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sujet->getId(), $request->request->get('_token'))) {
            $from =  $request->query->get('from');

            // verif si il a dejà voté pour la pétition
            $soutien = $soutienSujetRepo->searchSoutienSujet($this->getUser(), $sujet, $entityManager);

            // s'il a pas dejà voté pour
            if ($soutien == null) {
                // on crée un soutien en BDD
                $soutienSujetRepo->createSoutienSujet($this->getUser(), $sujet, $entityManager);

                $this->addFlash('sujet', 'Soutien apporté! Merci pour votre contribution.');
            } else {
                $this->addFlash('sujet', 'Vous avez déjà apporté votre soutien à ce post.');
            }
        }

        if ($from == 'sujet_show') {
            $parameters = array('sujet' =>  $sujet->getId());
        } else {
            $parameters = array(
                'amendementShow' =>  'false',
                'petition' =>  $petition
            );
        }

        return $this->redirectToRoute($from, $parameters);
    }
}
