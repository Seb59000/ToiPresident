<?php

namespace App\Controller;

use App\Entity\SoutienCandidat;
use App\Form\SoutienCandidatType;
use App\Repository\SoutienCandidatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/soutien/candidat")
 */
class SoutienCandidatController extends AbstractController
{
    /**
     * @Route("/", name="soutien_candidat_index", methods={"GET"})
     */
    public function index(SoutienCandidatRepository $soutienCandidatRepository): Response
    {
        return $this->render('soutien_candidat/index.html.twig', [
            'soutien_candidats' => $soutienCandidatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="soutien_candidat_show", methods={"GET"})
     */
    public function show(SoutienCandidat $soutienCandidat): Response
    {
        return $this->render('soutien_candidat/show.html.twig', [
            'soutien_candidat' => $soutienCandidat,
        ]);
    }
}
