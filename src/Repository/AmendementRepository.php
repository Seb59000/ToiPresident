<?php

namespace App\Repository;

use App\Entity\Amendement;
use App\Entity\Petition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Amendement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Amendement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Amendement[]    findAll()
 * @method Amendement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmendementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amendement::class);
    }


    /**
     * Returns number of Amendements
     * @return void 
     */
    public function getTotalAmendements($petition)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.petition = :petition')
            ->setParameter('petition', $petition);
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns all Amendements per page
     * @return void 
     */
    public function getPaginatedBestAmendements($page, $limit, $petition)
    {
        $query = $this->createQueryBuilder('a');
        $query->orderBy('a.nbsoutiens', 'DESC')
            ->where('a.petition = :petition')
            ->setParameter('petition', $petition)
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Amendement[] Returns an array of Amendement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Amendement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
