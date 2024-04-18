<?php

namespace App\Controller;

use App\Entity\Amendement;
use App\Entity\ComAmendement;
use App\Entity\SignalementComAmendement;
use App\Entity\SoutienComAmendement;
use App\Form\ComAmendementType;
use App\Repository\UserRepository;
use App\Repository\ComAmendementRepository;
use App\Repository\SoutienComAmendementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComAmendementController extends AbstractController
{
    /**
     * @Route("/espaceperso/commentaire/amendement/{amendement}", name="com_amendement_post", methods={"POST"})
     */
    public function post(Request $request, UserRepository $userRepo, EntityManagerInterface $entityManager, Amendement $amendement): Response
    {
        if ($this->isCsrfTokenValid('post' . $amendement->getId(), $request->request->get('_token'))) {
            $com = $request->request->get('commentaire');
            $commentaire = new ComAmendement();
            $commentaire->setAmendement($amendement);
            $user = $userRepo->find($this->getUser());
            $commentaire->setPseudo($user->getPseudo());
            $commentaire->setContenu($com);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            //creation soutien commentaire
            $soutien = new SoutienComAmendement();
            $soutien->setComAmendement($commentaire);
            $soutien->setUser($this->getUser());

            $entityManager->persist($soutien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('amendement_show', [
            'amendement' => $amendement->getId(),
        ]);
    }

    /**
     * @Route("/espaceperso/amendement/commentaire/soutien/{comAmendement}", name="com_amendement_soutien", methods={"POST"})
     */
    public function soutienCommentaire(Request $request, EntityManagerInterface $entityManager, SoutienComAmendementRepository $soutienComAmendementRepo, ComAmendement $comAmendement): Response
    {
        if ($this->isCsrfTokenValid('like' . $comAmendement->getId(), $request->request->get('_token'))) {
            // verif si il a dejà voté pour la pétition
            $soutien = $soutienComAmendementRepo->searchSoutienCommentaire($this->getUser(), $comAmendement, $entityManager);

            // s'il a pas dejà voté pour
            if ($soutien == null) {
                // on crée un soutien en BDD
                $soutienComAmendementRepo->createSoutienCommentaire($this->getUser(), $comAmendement, $entityManager);

                $this->addFlash('soutien', 'Soutien apporté!');
            } else {
                $this->addFlash('soutien', 'Vous avez déjà apporté votre soutien à ce post.');
            }
        }

        $parameters = array(
            'amendement' =>  $comAmendement->getAmendement()->getId(),
        );
        return $this->redirectToRoute('amendement_show', $parameters);
    }

    /**
     * @Route("/espaceperso/com/amendement/new/{amendement}", name="com_amendement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepo, EntityManagerInterface $entityManager, Amendement $amendement): Response
    {
        $comAmendement = new ComAmendement();
        $form = $this->createForm(ComAmendementType::class, $comAmendement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepo->find($this->getUser());
            $comAmendement->setPseudo($user->getPseudo());
            $comAmendement->setAmendement($amendement);
            $entityManager->persist($comAmendement);
            $entityManager->flush();

            return $this->redirectToRoute('com_amendement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('com_amendement/new.html.twig', [
            'com_amendement' => $comAmendement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/com/amendement/{commentaire}/edit/{signalement}", name="com_amendement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ComAmendement $commentaire, EntityManagerInterface $entityManager, SignalementComAmendement $signalement): Response
    {

        $form = $this->createForm(ComAmendementType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $entityManager->remove($signalement);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('com_amendement/edit.html.twig', [
            'com_amendement' => $commentaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/com/amendement/{id}", name="com_amendement_delete", methods={"POST"})
     */
    public function delete(Request $request, ComAmendement $comAmendement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comAmendement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comAmendement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin');
    }
}
