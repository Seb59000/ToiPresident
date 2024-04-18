<?php

namespace App\Repository;

use App\Entity\ComAmendement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComAmendement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComAmendement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComAmendement[]    findAll()
 * @method ComAmendement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComAmendementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComAmendement::class);
    }

    /**
     * Returns number of Commentaires
     * @return void 
     */
    public function getTotalCommentaires($amendement)
    {
        $query = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->where('c.amendement = :amendement')
            ->setParameter('amendement', $amendement);

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns all Commentaires per page
     * @return void 
     */
    public function getPaginatedCommentaires($page, $limit, $amendement)
    {
        $query = $this->createQueryBuilder('c');
        $query
            ->orderBy('c.datetime')
            ->where('c.amendement = :amendement')
            ->setParameter('amendement', $amendement)
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return ComAmendement[] Returns an array of ComAmendement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComAmendement
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
