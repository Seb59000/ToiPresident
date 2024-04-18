<?php

namespace App\Repository;

use App\Entity\Testrelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Testrelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testrelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testrelation[]    findAll()
 * @method Testrelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestrelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testrelation::class);
    }

    /**
     * Recherche les annonces en fonction du formulaire
     * @return void 
     */
    public function search($mots = null)
    {
        $query = $this->createQueryBuilder('t');
        if ($mots != null) {
            // (:mots boolean)>0' vient de doc officielle verifie si il y a un mot
            $query->andWhere('MATCH_AGAINST(t.titre, t.contenu) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Testrelation[] Returns an array of Testrelation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Testrelation
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
