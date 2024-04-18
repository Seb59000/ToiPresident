<?php

namespace App\Repository;

use App\Entity\Sujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sujet[]    findAll()
 * @method Sujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sujet::class);
    }

    /**
     * Returns number of Sujets
     * @return void 
     */
    public function getTotalSujets($petition)
    {
        $query = $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->where('s.petition = :petition')
            ->setParameter('petition', $petition);
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns all Sujets per page
     * @return void 
     */
    public function getPaginatedSujets($page, $limit, $petition)
    {
        $query = $this->createQueryBuilder('s');
        $query->orderBy('s.nbsoutiens', 'DESC')
            ->where('s.petition = :petition')
            ->setParameter('petition', $petition)
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Sujet[] Returns an array of Sujet objects
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
    public function findOneBySomeField($value): ?Sujet
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
