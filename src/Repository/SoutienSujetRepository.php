<?php

namespace App\Repository;

use App\Entity\SoutienSujet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SoutienSujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoutienSujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoutienSujet[]    findAll()
 * @method SoutienSujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoutienSujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoutienSujet::class);
    }

    /**
     * Recherche les Petition en fonction du formulaire
     * @return void 
     */
    public function searchSoutienSujet(User $user, $sujetId, $em)
    {
        $query = $em->createQuery('SELECT s FROM App\Entity\SoutienSujet s WHERE s.user = :userid AND s.sujet = :sujetid');
        $query->setParameters(array(
            'userid' => $user->getId(),
            'sujetid' => $sujetId,
        ));
        return $query->getResult();
    }

    /**
     * Recherche les Petition en fonction du formulaire
     * @return void 
     */
    public function createSoutienSujet(User $user, $sujet, $em)
    {
        // on ajoute un soutien pour la petition
        $soutien = new SoutienSujet();
        $soutien->setUser($user);
        $soutien->setSujet($sujet);
        $em->persist($soutien);
        $em->flush();

        // MAJ nb soutiens dans entité petition
        $this->MAJNbSoutien($sujet, $em);
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
    //  * @return SoutienSujet[] Returns an array of SoutienSujet objects
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
    public function findOneBySomeField($value): ?SoutienSujet
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
