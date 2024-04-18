<?php

namespace App\Repository;

use App\Entity\SoutienCandidat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SoutienCandidat|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoutienCandidat|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoutienCandidat[]    findAll()
 * @method SoutienCandidat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoutienCandidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoutienCandidat::class);
    }

    // /**
    //  * @return SoutienCandidat[] Returns an array of SoutienCandidat objects
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
    public function findOneBySomeField($value): ?SoutienCandidat
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
