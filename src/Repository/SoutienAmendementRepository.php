<?php

namespace App\Repository;

use App\Entity\SoutienAmendement;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SoutienAmendement|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoutienAmendement|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoutienAmendement[]    findAll()
 * @method SoutienAmendement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoutienAmendementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoutienAmendement::class);
    }

    /**
     * Recherche les Amendement en fonction du formulaire 
     * @return void 
     */
    public function searchSoutienAmendement(User $user, $amendementId, $em)
    {
        $query = $em->createQuery('SELECT s FROM App\Entity\SoutienAmendement s WHERE s.user = :userid AND s.amendement = :amendementid');
        $query->setParameters(array(
            'userid' => $user->getId(),
            'amendementid' => $amendementId,
        ));
        return $query->getResult();
    }

    /**
     * Recherche les Amendement en fonction du formulaire
     * @return void 
     */
    public function createSoutienAmendement($userid, $amendement, $em)
    {
        // on ajoute un soutien pour la petition
        $soutien = new SoutienAmendement();
        $soutien->setUser($userid);
        $soutien->setAmendement($amendement);
        $em->persist($soutien);
        $em->flush();

        // MAJ nb soutiens dans entité petition
        $this->MAJNbSoutien(true, $amendement, $em);
    }

    /**
     * MAJ nb soutiens dans entité petition
     * @return void 
     */
    public function MAJNbSoutien($incremente, $amendement, $em)
    {
        if ($incremente) {
            $nbsoutiens = $amendement->getNbsoutiens() + 1;
        } else {
            $nbsoutiens = $amendement->getNbsoutiens() - 1;
        }

        $amendement->setNbsoutiens($nbsoutiens);
        $em->persist($amendement);
        $em->flush();
    }

    /**
     * Recherche les Amendement en fonction du formulaire
     * @return void 
     */
    public function deleteSoutienAmendement($identifiant, $em)
    {

        $query = $em->createQuery(
            'DELETE 
               App\Entity\SoutienAmendement s 
             WHERE 
               s.id = :Id'
        )
            ->setParameter("Id", $identifiant);
        $query->execute();
    }

    // /**
    //  * @return SoutienAmendement[] Returns an array of SoutienAmendement objects
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
    public function findOneBySomeField($value): ?SoutienAmendement
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
