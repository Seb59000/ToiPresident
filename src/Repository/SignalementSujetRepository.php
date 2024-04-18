<?php

namespace App\Repository;

use App\Entity\SignalementSujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SignalementSujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignalementSujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignalementSujet[]    findAll()
 * @method SignalementSujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalementSujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignalementSujet::class);
    }

    // /**
    //  * @return SignalementSujet[] Returns an array of SignalementSujet objects
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
    public function findOneBySomeField($value): ?SignalementSujet
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
