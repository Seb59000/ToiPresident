<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="categories")
     */
    public function index(): Response
    {
        return $this->render('categories/index.html.twig');
    }

    /**
     * @Route("/{categorie}", name="cat_show", methods={"GET"})
     */
    public function show(PetitionRepository $petitionRepo, Request $request, Categorie $categorie): Response
    {
        // On définit le nombre d'éléments par page
        $limit = 10;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);
        $pageT2 = (int)$request->query->get("pageT2", 1);

        // On récupère le nombre total de petitions
        $total = $petitionRepo->getTotalPetitionsCategorie($categorie);

        // On récupère les petitions de la page 
        $bestPetitions = $petitionRepo->getPaginatedBestPetitionsCategorie($page, $limit, $categorie);

        // On récupère les petitions par date de la page 
        $petitionsDate = $petitionRepo->getPaginatedRecentPetitionsCategorie($pageT2, $limit, $categorie);

        return $this->render('categories/show.html.twig',  compact('petitionsDate', 'bestPetitions', 'total', 'limit', 'page', 'pageT2', 'categorie'));
    }
}
