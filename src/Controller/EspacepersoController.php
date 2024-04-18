<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PetitionRepository;
use App\Form\EditProfileType;
use App\Form\EspacePersoChangePassType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\PassworAuthenticatedUserInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Form\ChangePasswordFormType;
use App\Repository\SoutienPetitionRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/espaceperso")
 */
class EspacepersoController extends AbstractController
{
    /**
     * @Route("/", name="espaceperso")
     */
    public function index(PetitionRepository $petitionRepo, Request $request): Response
    {
        // On définit le nombre d'éléments par page
        $limit = 10;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);
        $pageT2 = 1;
        // On récupère le nombre total de petitions
        $total = $petitionRepo->getTotalPetitionsUser($this->getUser());

        // On récupère les petitions de la page 
        $bestPetitions = $petitionRepo->getPaginatedUserPetitions($page, $limit, $this->getUser());

        return $this->render('espaceperso/index.html.twig',  compact('bestPetitions', 'total', 'limit', 'page', 'pageT2'));
    }

    /**
     * @Route("/soutiens", name="espaceperso_soutiens")
     */
    public function indexPetitionsSoutenues(SoutienPetitionRepository $soutienPetitionRepo, Request $request, EntityManagerInterface $em): Response
    {
        // On définit le nombre d'éléments par page
        $limit = 10;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);
        $pageT2 = 1;

        // On récupère le nombre total de petitions
        $total = $soutienPetitionRepo->getTotalPetitionsSoutenues($this->getUser());

        // On récupère les petitions de la page 
        $bestPetitions = $soutienPetitionRepo->getPaginatedUserPetitionsSoutenues($page, $limit, $this->getUser(), $em);

        return $this->render('espaceperso/index_soutiens.html.twig',  compact('bestPetitions', 'total', 'limit', 'page', 'pageT2'));
    }

    /**
     * @Route("/edit", name="edit_profile")
     */
    public function editProfile(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('espaceperso');
        }

        return $this->render('espaceperso/editProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editPass", name="modif_pass")
     */
    public function editPass(ManagerRegistry $doctrine, Request $request,  UserPasswordHasherInterface $userPasswordHasher,  EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            $user = $entityManager->getRepository(User::class)->find($this->getUser());

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->flush();

            return $this->redirectToRoute('espaceperso');
        }

        return $this->renderForm('espaceperso/editPass.html.twig', [
            'form' => $form,
        ]);
    }
}
