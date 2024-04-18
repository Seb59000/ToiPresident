<?php

namespace App\Repository;

use App\Entity\SignalementCommentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SignalementCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignalementCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignalementCommentaire[]    findAll()
 * @method SignalementCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalementCommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignalementCommentaire::class);
    }

    // /**
    //  * @return SignalementCommentaire[] Returns an array of SignalementCommentaire objects
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
    public function findOneBySomeField($value): ?SignalementCommentaire
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
