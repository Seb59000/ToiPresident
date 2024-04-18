<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PetitionRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PetitionRepository $petitionRepo, Request $request): Response
    {
        // On définit le nombre d'éléments par page
        $limit = 10;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);
        $pageT2 = (int)$request->query->get("pageT2", 1);

        // On récupère le nombre total de petitions
        $total = $petitionRepo->getTotalPetitions();

        // On récupère les petitions de la page 
        $bestPetitions = $petitionRepo->getPaginatedBestPetitions($page, $limit);

        // On récupère les petitions par date de la page 
        $petitionsDate = $petitionRepo->getPaginatedRecentPetitions($pageT2, $limit);

        return $this->render('home/index.html.twig',  compact('petitionsDate', 'bestPetitions', 'total', 'limit', 'page', 'pageT2'));
    }
}
