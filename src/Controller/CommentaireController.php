<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\SignalementCommentaire;
use App\Entity\SoutienCommentaire;
use App\Entity\Sujet;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\SoutienCommentaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    /**
     * @Route("/espaceperso/commentaire/new/{sujet}", name="commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepo, Sujet $sujet): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setSujet($sujet);
            $user = $userRepo->find($this->getUser());
            $commentaire->setPseudo($user->getPseudo());
            $entityManager->persist($commentaire);
            $entityManager->flush();

            //creation soutien commentaire
            $soutien = new SoutienCommentaire();
            $soutien->setCommentaire($commentaire);
            $soutien->setUser($this->getUser());

            $entityManager->persist($soutien);
            $entityManager->flush();

            return $this->redirectToRoute('sujet_show', [
                'sujet' => $sujet->getId(),
                'petition' => $sujet->getPetition()->getId(),
            ]);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/espaceperso/commentaire/{id}", name="commentaire_post", methods={"POST"})
     */
    public function post(Request $request, UserRepository $userRepo, Sujet $sujet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('post' . $sujet->getId(), $request->request->get('_token'))) {
            $com = $request->request->get('commentaire');
            $commentaire = new Commentaire();
            $commentaire->setSujet($sujet);
            $user = $userRepo->find($this->getUser());
            $commentaire->setPseudo($user->getPseudo());
            $commentaire->setContenu($com);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            //creation soutien commentaire
            $soutien = new SoutienCommentaire();
            $soutien->setCommentaire($commentaire);
            $soutien->setUser($this->getUser());

            $entityManager->persist($soutien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sujet_show', [
            'sujet' => $sujet->getId(),
            'petition' => $sujet->getPetition()->getId(),
        ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/admin/commentaire/{commentaire}/edit/{signalement}", name="commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, SignalementCommentaire $signalement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $entityManager->remove($signalement);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/commentaire/{id}", name="commentaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/espaceperso/commentaire/soutien/{commentaire}", name="commentaire_soutien", methods={"POST"})
     */
    public function soutienCommentaire(Request $request, EntityManagerInterface $entityManager, SoutienCommentaireRepository $soutienComRepo, Commentaire $commentaire): Response
    {
        if ($this->isCsrfTokenValid('like' . $commentaire->getId(), $request->request->get('_token'))) {
            // verif si il a dejà voté pour la pétition
            $soutien = $soutienComRepo->searchSoutienCommentaire($this->getUser(), $commentaire, $entityManager);

            // s'il a pas dejà voté pour
            if ($soutien == null) {
                // on crée un soutien en BDD
                $soutienComRepo->createSoutienCommentaire($this->getUser(), $commentaire, $entityManager);

                $this->addFlash('commentaire', 'Soutien apporté! Merci pour votre contribution.');
            } else {
                $this->addFlash('commentaire', 'Vous avez déjà apporté votre soutien à ce post.');
            }
        }

        $parameters = array(
            'petition' =>  $commentaire->getId(),
            'sujet' =>  $commentaire->getSujet()->getId()
        );
        return $this->redirectToRoute('sujet_show', $parameters);
    }
}
