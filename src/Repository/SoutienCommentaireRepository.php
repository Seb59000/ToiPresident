<?php

namespace App\Repository;

use App\Entity\SoutienCommentaire;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SoutienCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoutienCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoutienCommentaire[]    findAll()
 * @method SoutienCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoutienCommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoutienCommentaire::class);
    }

    /**
     * Recherche les Petition en fonction du formulaire
     * @return void 
     */
    public function searchSoutienCommentaire(User $user, $commentaireid, $em)
    {
        $query = $em->createQuery('SELECT s FROM App\Entity\SoutienCommentaire s WHERE s.user = :userid AND s.commentaire = :commentaireid');
        $query->setParameters(array(
            'userid' => $user->getId(),
            'commentaireid' => $commentaireid,
        ));
        return $query->getResult();
    }

    /**
     * Recherche les Petition en fonction du formulaire
     * @return void 
     */
    public function createSoutienCommentaire(User $user, $commentaire, $em)
    {
        // on ajoute un soutien pour la petition
        $soutien = new SoutienCommentaire();
        $soutien->setUser($user);
        $soutien->setCommentaire($commentaire);
        $em->persist($soutien);
        $em->flush();

        // MAJ nb soutiens dans entité petition
        $this->MAJNbSoutien($commentaire, $em);
    }

    /**
     * MAJ nb soutiens dans entité petition
     * @return void 
     */
    public function MAJNbSoutien($petition, $em)
    {
        $nbsoutiens = $petition->getNbsoutiens() + 1;

        $petition->setNbsoutiens($nbsoutiens);
        $em->persist($petition);
        $em->flush();
    }

    // /**
    //  * @return SoutienCommentaire[] Returns an array of SoutienCommentaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SoutienCommentaire
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
