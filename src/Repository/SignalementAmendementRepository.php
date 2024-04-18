<?php

namespace App\Repository;

use App\Entity\SignalementAmendement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SignalementAmendement|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignalementAmendement|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignalementAmendement[]    findAll()
 * @method SignalementAmendement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalementAmendementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignalementAmendement::class);
    }

    // /**
    //  * @return SignalementAmendement[] Returns an array of SignalementAmendement objects
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
    public function findOneBySomeField($value): ?SignalementAmendement
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
