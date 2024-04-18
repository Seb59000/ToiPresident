<?php

namespace App\Repository;

use App\Entity\SignalementPetition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SignalementPetition|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignalementPetition|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignalementPetition[]    findAll()
 * @method SignalementPetition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalementPetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignalementPetition::class);
    }

    // /**
    //  * @return SignalementPetition[] Returns an array of SignalementPetition objects
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
    public function findOneBySomeField($value): ?SignalementPetition
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
