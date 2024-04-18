<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Categorie;
use App\Entity\Petition;
use App\Entity\SoutienPetition;
use App\Form\PetitionType;
use App\Repository\PetitionRepository;
use App\Repository\AmendementRepository;

use App\Repository\SoutienPetitionRepository;
use App\Repository\OpposantPetitionRepository;
use App\Repository\SujetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RechercheType;

class PetitionController extends AbstractController
{
    /**
     * @Route("/espaceperso/petition/recherche/", name="recherche_new_petition")
     */
    public function recherche(PetitionRepository $petitionRepo, Request $request): Response
    {
        $form = $this->createForm(RechercheType::class);

        $search = $form->handleRequest($request);

        $petitions = null;

        if ($form->isSubmitted() && $form->isValid()) {
            // On recherche les annonces correspondant aux mots clés
            $petitions = $petitionRepo->search(
                $search->get('mots')->getData()
            );
        }

        return $this->render('petition/recherche.html.twig', [
            'controller_name' => 'RechercheController',
            'petitions' => $petitions,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/espaceperso/petition/new", name="petition_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $petition = new Petition();
        $form = $this->createForm(PetitionType::class, $petition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on vérifie qu'il y a un contenu
            $contenu = $form->getData()->getContenu();
            if ($contenu == null) {
                $this->addFlash('alert', 'Veuillez décrire votre proposition.');
            } else {
                $petition->setInstigateur($this->getUser());
                $entityManager->persist($petition);
                $entityManager->flush();

                //creation soutien petition
                $soutienPetition = new SoutienPetition();
                $soutienPetition->setPetition($petition);
                $soutienPetition->setUser($this->getUser());

                $entityManager->persist($soutienPetition);
                $entityManager->flush();

                return $this->redirectToRoute('espaceperso');
            }
        }

        return $this->renderForm('petition/new.html.twig', [
            'petition' => $petition,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/petition/{petition}/{amendementShow}", name="petition_show", methods={"GET"})
     */
    public function show(Petition $petition, AmendementRepository $amendementRepo, SujetRepository $sujetRepo, Request $request, $amendementShow): Response
    {
        $url = $request->getSchemeAndHttpHost() . $request->getPathInfo();

        $petitionId = $petition->getId();

        // On définit le nombre d'éléments par page
        $limit = 15;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);
        $pageT2 = (int)$request->query->get("pageT2", 1);

        // On récupère le nombre total d'amendements pour la petition
        $total = $amendementRepo->getTotalAmendements($petitionId);

        // On récupère les amendements de la page
        $amendements = $amendementRepo->getPaginatedBestAmendements($page, $limit, $petitionId);

        // On récupère le nombre total de sujets pour la petition
        $totalT2 = $sujetRepo->getTotalSujets($petitionId);

        // On récupère les sujets de la page
        $sujets = $sujetRepo->getPaginatedSujets($pageT2, $limit, $petitionId);

        return $this->render('petition/show.html.twig',  compact('petition', 'sujets', 'amendements', 'totalT2',  'total', 'limit', 'page', 'pageT2', 'amendementShow', 'url'));
    }

    /**
     * @Route("/espaceperso/soutien/{petition}/{from}/{candidat}/{categorie}/{page}/{pageT2}", name="petition_soutien", methods={"POST"})
     */
    public function soutienPetition(Candidat $candidat, Categorie $categorie, Request $request, Petition $petition, EntityManagerInterface $entityManager, SoutienPetitionRepository $soutienPetitionRepo, OpposantPetitionRepository $opposantPetitionRepo, $from, $page, $pageT2): Response
    {
        if ($this->isCsrfTokenValid('delete' . $petition->getId(), $request->request->get('_token'))) {
            // verif si il a dejà voté pour la pétition
            $soutien = $soutienPetitionRepo->searchSoutienPetition($this->getUser(), $petition->getId(), $entityManager);

            // s'il a pas dejà voté pour
            if ($soutien == null) {
                // on crée un soutien en BDD
                $soutienPetitionRepo->createSoutienPetition($this->getUser(), $petition, $entityManager);

                $this->addFlash('success', 'Soutien apporté! Merci pour votre contribution.');

                // verif si il a changé d'avis (opposant a la pétition avant)
                $opposition = $opposantPetitionRepo->searchOpposantPetition($this->getUser(), $petition->getId(), $entityManager);

                // si il a changé d'avis
                if ($opposition != null) {
                    // on supprime son précedent vote
                    $soutienPetition = $opposantPetitionRepo->find($opposition[0]->getId());
                    $identifiant = $soutienPetition->getId();

                    $opposantPetitionRepo->deleteOpposantPetition($identifiant, $entityManager);

                    // MAJ nb opposant --
                    $opposantPetitionRepo->MAJNbOpposant(false, $petition, $entityManager);
                }
            } else {
                $this->addFlash('success', 'Vous avez déjà apporté votre soutien à cette pétition.');
            }
        }

        // redirection en fonction page d'origine
        if ($from == 'candidat_show_categorie') {
            $parameters = array(
                'candidat' =>  $candidat->getId(),
                'categorie' =>  $categorie->getId()
            );
            return $this->redirectToRoute($from, $parameters);
        } else if ($from == 'candidat_show') {
            $parameters = array(
                'candidat' =>  $candidat->getId()
            );
            return $this->redirectToRoute($from, $parameters);
        } else if ($from == 'petition_show') {
            $parameters = array(
                'amendementShow' =>  'false',
                'petition' =>  $petition->getId()
            );
            return $this->redirectToRoute($from, $parameters);
        } else {
            $parameters = array(
                'page' =>  $page,
                'pageT2' =>  $pageT2
            );
            return $this->redirectToRoute($from, $parameters);
        }
    }

    /**
     * @Route("/espaceperso/opposition/{petition}/{from}/{candidat}/{categorie}/{page}/{pageT2}", name="petition_opposition", methods={"POST"})
     */
    public function oppositionPetition(Candidat $candidat, Categorie $categorie, Request $request, Petition $petition, EntityManagerInterface $entityManager, SoutienPetitionRepository $soutienPetitionRepo, OpposantPetitionRepository $opposantPetitionRepo, $from, $page, $pageT2): Response
    {
        if ($this->isCsrfTokenValid('delete' . $petition->getId(), $request->request->get('_token'))) {
            // verif si il s'est pas dejà opposé à la pétition
            $opposition = $opposantPetitionRepo->searchOpposantPetition($this->getUser(), $petition->getId(), $entityManager);

            // s'il s'est pas dejà opposé
            if ($opposition == null) {
                // on crée un opposant en BDD
                $opposantPetitionRepo->createOpposantPetition($this->getUser(), $petition, $entityManager);

                $this->addFlash('success', 'Votre vote a été pris en compte! Merci pour votre participation.');

                // verif si il a changé d'avis (soutenu la pétition avant)
                $soutien = $soutienPetitionRepo->searchSoutienPetition($this->getUser(), $petition->getId(), $entityManager);

                // si il a changé d'avis
                if ($soutien != null) {
                    // on supprime son précedent vote
                    $soutienPetition = $soutienPetitionRepo->find($soutien[0]->getId());
                    $identifiant = $soutienPetition->getId();

                    $soutienPetitionRepo->deleteSoutienPetition($identifiant, $entityManager);

                    // MAJ nb soutiens --
                    $soutienPetitionRepo->MAJNbSoutien(false, $petition, $entityManager);
                }
            } else {
                $this->addFlash('success', 'Vous avez déjà manifesté votre opposition à cette pétition.');
            }
        }

        // redirection en fonction page d'origine
        if ($from == 'candidat_show_categorie') {
            $parameters = array(
                'candidat' =>  $candidat->getId(),
                'categorie' =>  $categorie->getId()
            );
            return $this->redirectToRoute($from, $parameters);
        } else if ($from == 'candidat_show') {
            $parameters = array(
                'candidat' =>  $candidat->getId()
            );
            return $this->redirectToRoute($from, $parameters);
        } else if ($from == 'petition_show') {
            $parameters = array(
                'amendementShow' =>  'false',
                'petition' =>  $petition->getId()
            );
            return $this->redirectToRoute($from, $parameters);
        } else {
            $parameters = array(
                'page' =>  $page,
                'pageT2' =>  $pageT2
            );
            return $this->redirectToRoute($from, $parameters);
        }
    }
}
