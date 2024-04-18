<?php

namespace App\Controller;

use App\Repository\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RechercheType;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", name="recherche")
     */
    public function index(PetitionRepository $petitionRepo, Request $request): Response
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

        return $this->render('recherche/index.html.twig', [
            'petitions' => $petitions,
            'form' => $form->createView()
        ]);
    }
}
