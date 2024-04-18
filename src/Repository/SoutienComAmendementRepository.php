<?php

namespace App\Repository;

use App\Entity\SoutienComAmendement;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SoutienComAmendement|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoutienComAmendement|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoutienComAmendement[]    findAll()
 * @method SoutienComAmendement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoutienComAmendementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoutienComAmendement::class);
    }

    /**
     * Recherche les Petition en fonction du formulaire
     * @return void 
     */
    public function searchSoutienCommentaire(User $user, $commentaireid, $em)
    {
        $query = $em->createQuery('SELECT s FROM App\Entity\SoutienComAmendement s WHERE s.user = :userid AND s.comAmendement = :commentaireid');
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
        $soutien = new SoutienComAmendement();
        $soutien->setUser($user);
        $soutien->setComAmendement($commentaire);
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
    //  * @return SoutienComAmendement[] Returns an array of SoutienComAmendement objects
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
    public function findOneBySomeField($value): ?SoutienComAmendement
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
