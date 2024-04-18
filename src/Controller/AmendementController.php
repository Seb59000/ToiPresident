<?php

namespace App\Controller;

use App\Entity\Amendement;
use App\Entity\Petition;
use App\Entity\SignalementAmendement;
use App\Entity\SoutienAmendement;
use App\Form\AmendementType;
use App\Repository\ComAmendementRepository;
use App\Repository\PetitionRepository;
use App\Repository\SoutienAmendementRepository;
use App\Repository\OpposantAmendementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AmendementController extends AbstractController
{
    /**
     * @Route("/espaceperso/amendement/new/{petition}", name="amendement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, PetitionRepository $petitionRepo, Petition $petition): Response
    {
        $amendement = new Amendement();
        $form = $this->createForm(AmendementType::class, $amendement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on vérifie qu'il y a un contenu
            $contenu = $form->getData()->getContenu();
            if ($contenu == null) {
                $this->addFlash('alert', 'Veuillez décrire votre amendement.');
            } else {
                $petition = $petitionRepo->find($petition->getId());
                $amendement->setPetition($petition);
                $entityManager->persist($amendement);
                $entityManager->flush();

                //creation soutien amendement
                $soutienAmendement = new SoutienAmendement();
                $soutienAmendement->setAmendement($amendement);
                $soutienAmendement->setUser($this->getUser());

                $entityManager->persist($soutienAmendement);
                $entityManager->flush();

                $parameters = array(
                    'amendementShow' =>  'true',
                    'petition' =>  $petition->getId()
                );
                return $this->redirectToRoute('petition_show', $parameters);
            }
        }

        return $this->renderForm('amendement/new.html.twig', [
            'amendement' => $amendement,
            'titrepetition' => $petition->getTitre(),
            'form' => $form,
        ]);
    }

    /**
     * @Route("/amendement/{amendement}", name="amendement_show", methods={"GET"})
     */
    public function show(Request $request, ComAmendementRepository $comAmendementRepo, Amendement $amendement): Response
    {
        // On définit le nombre d'éléments par page
        $limit = 20;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);

        // On récupère le nombre total de commentaires pour le sujet
        $total = $comAmendementRepo->getTotalCommentaires($amendement);

        // On récupère les commentaires de la page
        $commentaires = $comAmendementRepo->getPaginatedCommentaires($page, $limit, $amendement);

        return $this->renderForm('amendement/show.html.twig', [
            'amendement' => $amendement,
            'petition' => $amendement->getPetition(),
            'commentaires' => $commentaires,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
        ]);

        return $this->render('amendement/show.html.twig', [
            'titrepetition' => $amendement->getPetition()->getTitre(),
            'amendement' => $amendement,
        ]);
    }

    /**
     * @Route("admin/amendement/{id}/edit/{signalement}", name="amendement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Amendement $amendement, SignalementAmendement $signalement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AmendementType::class, $amendement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $entityManager->remove($signalement);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('amendement/edit.html.twig', [
            'amendement' => $amendement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/amendement/{id}", name="amendement_delete", methods={"POST"})
     */
    public function delete(Request $request, Amendement $amendement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $amendement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($amendement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin');
    }


    /**
     * @Route("/espaceperso/amendement/soutien/{amendement}/{from}", name="amendement_soutien", methods={"POST"})
     */
    public function soutienAmendement(Request $request, Amendement $amendement, EntityManagerInterface $entityManager, SoutienAmendementRepository $soutienAmendementRepo, OpposantAmendementRepository $opposantAmendementRepo, $from): Response
    {
        if ($this->isCsrfTokenValid('delete' . $amendement->getId(), $request->request->get('_token'))) {
            // verif si il a dejà voté pour l amendement
            $soutien = $soutienAmendementRepo->searchSoutienAmendement($this->getUser(), $amendement->getId(), $entityManager);
            // s'il a pas dejà voté pour
            if ($soutien == null) {
                // on crée un soutien en BDD
                $soutienAmendementRepo->createSoutienAmendement($this->getUser(), $amendement, $entityManager);

                $this->addFlash('amendement', 'Soutien apporté! Merci pour votre contribution.');

                // verif si il a changé d'avis (opposant a l amendement avant)
                $opposition = $opposantAmendementRepo->searchOpposantAmendement($this->getUser(), $amendement->getId(), $entityManager);

                // si il a changé d'avis
                if ($opposition != null) {
                    // on supprime son précedent vote
                    $soutienPetition = $opposantAmendementRepo->find($opposition[0]->getId());
                    $identifiant = $soutienPetition->getId();

                    $opposantAmendementRepo->deleteOpposantAmendement($identifiant, $entityManager);

                    // MAJ nb opposant --
                    $opposantAmendementRepo->MAJNbOpposant(false, $amendement, $entityManager);
                }
            } else {
                $this->addFlash('amendement', 'Vous avez déjà apporté votre soutien à cet amendement.');
            }
        }
        if ($from == 'petition_show') {
            $parameters = array(
                'amendementShow' =>  'true',
                'petition' =>  $amendement->getPetition()->getId()
            );
            return $this->redirectToRoute($from, $parameters);
        } else {
            $parameters = array('amendement' =>  $amendement->getId());
            return $this->redirectToRoute('amendement_show', $parameters);
        }
    }

    /**
     * @Route("/espaceperso/amendement/opposition/{amendement}/{from}", name="amendement_opposition", methods={"POST"})
     */
    public function oppositionAmendement(Request $request, Amendement $amendement, EntityManagerInterface $entityManager, SoutienAmendementRepository $soutienAmendementRepo, OpposantAmendementRepository $opposantAmendementRepo, $from): Response
    {
        if ($this->isCsrfTokenValid('delete' . $amendement->getId(), $request->request->get('_token'))) {
            // verif si il s'est pas dejà opposé à l amendement
            $opposition = $opposantAmendementRepo->searchOpposantAmendement($this->getUser(), $amendement->getId(), $entityManager);
            // s'il s'est pas dejà opposé
            if ($opposition == null) {
                // on crée un opposant en BDD
                $opposantAmendementRepo->createOpposantAmendement($this->getUser(), $amendement, $entityManager);

                $this->addFlash('amendement', 'Votre vote a été pris en compte! Merci pour votre participation.');

                // verif si il a changé d'avis (soutenu l amendement avant)
                $soutien = $soutienAmendementRepo->searchSoutienAmendement($this->getUser(), $amendement->getId(), $entityManager);

                // si il a changé d'avis
                if ($soutien != null) {
                    // on supprime son précedent vote
                    $soutienPetition = $soutienAmendementRepo->find($soutien[0]->getId());
                    $identifiant = $soutienPetition->getId();

                    $soutienAmendementRepo->deleteSoutienAmendement($identifiant, $entityManager);

                    // MAJ nb soutiens --
                    $soutienAmendementRepo->MAJNbSoutien(false, $amendement, $entityManager);
                }
            } else {
                $this->addFlash('amendement', 'Vous avez déjà manifesté votre opposition à cet amendement.');
            }
        }
        if ($from == 'petition_show') {
            $parameters = array(
                'amendementShow' =>  'true',
                'petition' =>  $amendement->getPetition()->getId()
            );
            return $this->redirectToRoute($from, $parameters);
        } else {
            $parameters = array('amendement' =>  $amendement->getId());
            return $this->redirectToRoute('amendement_show', $parameters);
        }
    }
}
