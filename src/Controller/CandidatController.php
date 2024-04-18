<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Categorie;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use App\Repository\CategorieRepository;
use App\Repository\PetitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/candidat")
 */
class CandidatController extends AbstractController
{
    /**
     * @Route("/{candidat}", name="candidat_show", methods={"GET"})
     */
    public function show(Candidat $candidat, PetitionRepository $petitionRepo, Request $request, EntityManagerInterface $em, CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        // On définit le nombre d'éléments par page
        $limit = 10;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);
        $pageT2 = 1;

        // On récupère le nombre total de petitions
        $total = $petitionRepo->getTotalPetitionsCandidat($candidat, $em);

        // On récupère les petitions de la page 
        $bestPetitions = $petitionRepo->getPaginatedPetitionsCandidat($page, $limit, $candidat, $em);

        return $this->render('candidat/show.html.twig',  compact('bestPetitions', 'total', 'limit', 'page', 'pageT2', 'candidat', 'categories'));
    }

    /**
     * @Route("/{candidat}/{categorie}", name="candidat_show_categorie", methods={"GET"})
     */
    public function showCategorie(Candidat $candidat, Categorie $categorie, PetitionRepository $petitionRepo, Request $request, EntityManagerInterface $em, CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        // On définit le nombre d'éléments par page
        $limit = 10;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);
        $pageT2 = 1;

        // On récupère le nombre total de petitions
        $total = $petitionRepo->getTotalPetitionsCandidatParCategorie($candidat, $categorie, $em);

        // On récupère les petitions de la page 
        $bestPetitions = $petitionRepo->getPaginatedPetitionsCandidatParCategorie($page, $limit, $candidat, $categorie, $em);

        return $this->render('candidat/show_categorie.html.twig',  compact('bestPetitions', 'total', 'limit', 'page', 'pageT2', 'candidat', 'categories', 'categorie'));
    }
}
