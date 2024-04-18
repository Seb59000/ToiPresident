<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use App\Form\PetitionType;
use App\Entity\Petition;
use App\Entity\SignalementPetition;
use App\Entity\SoutienCandidat;
use App\Repository\CandidatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PetitionRepository;
use App\Repository\SignalementAmendementRepository;
use App\Repository\SignalementComAmendementRepository;
use App\Repository\SignalementCommentaireRepository;
use App\Repository\SignalementPetitionRepository;
use App\Repository\SignalementSujetRepository;
use App\Repository\SoutienCandidatRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RechercheType;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/petition/{id}/edit/{signalement}", name="petition_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Petition $petition, SignalementPetition $signalement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PetitionType::class, $petition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $entityManager->remove($signalement);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('petition/edit.html.twig', [
            'petition' => $petition,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/petition/edit/candidat/{petition}", name="petition_edit_candidat", methods={"GET", "POST"})
     */
    public function editFromCandidat(Request $request, Petition $petition, EntityManagerInterface $entityManager, CandidatRepository $candidatRepository): Response
    {
        //dd('yo');

        $candidats = $candidatRepository->findAll();
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

                // partie soutien candidat
                // candidat1
                $candidat1 = $request->request->get('candidat1');
                if ($candidat1) {
                    $soutienCandidat1 = new SoutienCandidat();
                    $soutienCandidat1->setCandidat($candidats[0]);
                    $soutienCandidat1->setPetition($petition);
                    $entityManager->persist($soutienCandidat1);
                    $entityManager->flush();
                }
                // candidat2
                $candidat2 = $request->request->get('candidat2');
                if ($candidat2) {
                    $soutienCandidat2 = new SoutienCandidat();
                    $soutienCandidat2->setCandidat($candidats[1]);
                    $soutienCandidat2->setPetition($petition);
                    $entityManager->persist($soutienCandidat2);
                    $entityManager->flush();
                }
                // candidat3
                $candidat3 = $request->request->get('candidat3');
                if ($candidat3) {
                    $soutienCandidat3 = new SoutienCandidat();
                    $soutienCandidat3->setCandidat($candidats[2]);
                    $soutienCandidat3->setPetition($petition);
                    $entityManager->persist($soutienCandidat3);
                    $entityManager->flush();
                }
                // candidat4
                $candidat4 = $request->request->get('candidat4');
                if ($candidat4) {
                    $soutienCandidat4 = new SoutienCandidat();
                    $soutienCandidat4->setCandidat($candidats[3]);
                    $soutienCandidat4->setPetition($petition);
                    $entityManager->persist($soutienCandidat4);
                    $entityManager->flush();
                }
                // candidat5
                $candidat5 = $request->request->get('candidat5');
                if ($candidat5) {
                    $soutienCandidat5 = new SoutienCandidat();
                    $soutienCandidat5->setCandidat($candidats[4]);
                    $soutienCandidat5->setPetition($petition);
                    $entityManager->persist($soutienCandidat5);
                    $entityManager->flush();
                }
                // candidat6
                $candidat6 = $request->request->get('candidat6');
                if ($candidat6) {
                    $soutienCandidat6 = new SoutienCandidat();
                    $soutienCandidat6->setCandidat($candidats[5]);
                    $soutienCandidat6->setPetition($petition);
                    $entityManager->persist($soutienCandidat6);
                    $entityManager->flush();
                }
                // candidat7
                $candidat7 = $request->request->get('candidat7');
                if ($candidat7) {
                    $soutienCandidat7 = new SoutienCandidat();
                    $soutienCandidat7->setCandidat($candidats[6]);
                    $soutienCandidat7->setPetition($petition);
                    $entityManager->persist($soutienCandidat7);
                    $entityManager->flush();
                }
                // candidat8
                $candidat8 = $request->request->get('candidat8');
                if ($candidat8) {
                    $soutienCandidat8 = new SoutienCandidat();
                    $soutienCandidat8->setCandidat($candidats[7]);
                    $soutienCandidat8->setPetition($petition);
                    $entityManager->persist($soutienCandidat8);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('admin');
            }
        }

        return $this->renderForm('petition/new_option_candidat.html.twig', [
            'petition' => $petition,
            'candidats' => $candidats,
            'form' => $form,
        ]);


        $form = $this->createForm(PetitionType::class, $petition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('petition/edit.html.twig', [
            'petition' => $petition,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/petition/{id}", name="petition_delete", methods={"POST"})
     */
    public function delete(Request $request, Petition $petition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $petition->getId(), $request->request->get('_token'))) {
            $entityManager->remove($petition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/candidat/", name="candidat_index", methods={"GET"})
     */
    public function candidatsIndex(CandidatRepository $candidatRepository): Response
    {
        return $this->render('candidat/index.html.twig', [
            'candidats' => $candidatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/candidat/new", name="candidat_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidat);
            $entityManager->flush();

            return $this->redirectToRoute('candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/candidat/{id}/edit", name="candidat_edit", methods={"GET", "POST"})
     */
    public function candidatEdit(Request $request, Candidat $candidat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/candidat/{id}", name="candidat_delete", methods={"POST"})
     */
    public function candidatDelete(Request $request, Candidat $candidat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($candidat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidat_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/soutien/candidat/new", name="soutien_candidat_new", methods={"GET", "POST"})
     */
    public function newSoutienCandidat(Request $request, EntityManagerInterface $entityManager): Response
    {
        $soutienCandidat = new SoutienCandidat();
        $form = $this->createForm(SoutienCandidatType::class, $soutienCandidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($soutienCandidat);
            $entityManager->flush();

            return $this->redirectToRoute('soutien_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('soutien_candidat/new.html.twig', [
            'soutien_candidat' => $soutienCandidat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/soutien/candidat/{id}/edit", name="soutien_candidat_edit", methods={"GET", "POST"})
     */
    public function editSoutien_candidat(Request $request, SoutienCandidat $soutienCandidat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SoutienCandidatType::class, $soutienCandidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('soutien_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('soutien_candidat/edit.html.twig', [
            'soutien_candidat' => $soutienCandidat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/soutien/candidat/{id}", name="soutien_candidat_delete", methods={"POST"})
     */
    public function deleteSoutien_candidat(Request $request, SoutienCandidat $soutienCandidat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $soutienCandidat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($soutienCandidat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('soutien_candidat_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/petition/new/option/candidat", name="petition_new_option_candidat", methods={"GET", "POST"})
     */
    public function newPetitionOptionCandidat(Request $request, EntityManagerInterface $entityManager, CandidatRepository $candidatRepository): Response
    {
        $candidats = $candidatRepository->findAll();
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

                // partie soutien candidat
                // candidat1
                $candidat1 = $request->request->get('candidat1');
                if ($candidat1) {
                    $soutienCandidat1 = new SoutienCandidat();
                    $soutienCandidat1->setCandidat($candidats[0]);
                    $soutienCandidat1->setPetition($petition);
                    $entityManager->persist($soutienCandidat1);
                    $entityManager->flush();
                }
                // candidat2
                $candidat2 = $request->request->get('candidat2');
                if ($candidat2) {
                    $soutienCandidat2 = new SoutienCandidat();
                    $soutienCandidat2->setCandidat($candidats[1]);
                    $soutienCandidat2->setPetition($petition);
                    $entityManager->persist($soutienCandidat2);
                    $entityManager->flush();
                }
                // candidat3
                $candidat3 = $request->request->get('candidat3');
                if ($candidat3) {
                    $soutienCandidat3 = new SoutienCandidat();
                    $soutienCandidat3->setCandidat($candidats[2]);
                    $soutienCandidat3->setPetition($petition);
                    $entityManager->persist($soutienCandidat3);
                    $entityManager->flush();
                }
                // candidat4
                $candidat4 = $request->request->get('candidat4');
                if ($candidat4) {
                    $soutienCandidat4 = new SoutienCandidat();
                    $soutienCandidat4->setCandidat($candidats[3]);
                    $soutienCandidat4->setPetition($petition);
                    $entityManager->persist($soutienCandidat4);
                    $entityManager->flush();
                }
                // candidat5
                $candidat5 = $request->request->get('candidat5');
                if ($candidat5) {
                    $soutienCandidat5 = new SoutienCandidat();
                    $soutienCandidat5->setCandidat($candidats[4]);
                    $soutienCandidat5->setPetition($petition);
                    $entityManager->persist($soutienCandidat5);
                    $entityManager->flush();
                }
                // candidat6
                $candidat6 = $request->request->get('candidat6');
                if ($candidat6) {
                    $soutienCandidat6 = new SoutienCandidat();
                    $soutienCandidat6->setCandidat($candidats[5]);
                    $soutienCandidat6->setPetition($petition);
                    $entityManager->persist($soutienCandidat6);
                    $entityManager->flush();
                }
                // candidat7
                $candidat7 = $request->request->get('candidat7');
                if ($candidat7) {
                    $soutienCandidat7 = new SoutienCandidat();
                    $soutienCandidat7->setCandidat($candidats[6]);
                    $soutienCandidat7->setPetition($petition);
                    $entityManager->persist($soutienCandidat7);
                    $entityManager->flush();
                }
                // candidat8
                $candidat8 = $request->request->get('candidat8');
                if ($candidat8) {
                    $soutienCandidat8 = new SoutienCandidat();
                    $soutienCandidat8->setCandidat($candidats[7]);
                    $soutienCandidat8->setPetition($petition);
                    $entityManager->persist($soutienCandidat8);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('admin');
            }
        }

        return $this->renderForm('petition/new_option_candidat.html.twig', [
            'petition' => $petition,
            'candidats' => $candidats,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/recherche", name="recherche_admin")
     */
    public function rechercheAdmin(PetitionRepository $petitionRepo, Request $request): Response
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

        return $this->render('admin/recherche_admin.html.twig', [
            'petitions' => $petitions,
            'form' => $form->createView()
        ]);
    }

    private function ajoutSoutienCandidat()
    {
    }
}
