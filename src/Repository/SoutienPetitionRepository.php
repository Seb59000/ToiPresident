<?php

namespace App\Repository;

use App\Entity\SoutienPetition;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SoutienPetition|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoutienPetition|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoutienPetition[]    findAll()
 * @method SoutienPetition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoutienPetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoutienPetition::class);
    }

    /**
     * Recherche les Petition en fonction du formulaire
     * @return void 
     */
    public function searchSoutienPetition(User $user, $petitionId, $em)
    {
        $query = $em->createQuery('SELECT s FROM App\Entity\SoutienPetition s WHERE s.user = :userid AND s.petition = :petitionid');
        $query->setParameters(array(
            'userid' => $user,
            'petitionid' => $petitionId,
        ));
        return $query->getResult();
    }

    /**
     * Recherche les Petition en fonction du formulaire
     * @return void 
     */
    public function createSoutienPetition($userid, $petition, $em)
    {
        // on ajoute un soutien pour la petition
        $soutien = new SoutienPetition();
        $soutien->setUser($userid);
        $soutien->setPetition($petition);
        $em->persist($soutien);
        $em->flush();

        // MAJ nb soutiens dans entité petition
        $this->MAJNbSoutien(true, $petition, $em);
    }

    /**
     * MAJ nb soutiens dans entité petition
     * @return void 
     */
    public function MAJNbSoutien($incremente, $petition, $em)
    {
        if ($incremente) {
            $nbsoutiens = $petition->getNbsoutiens() + 1;
        } else {
            $nbsoutiens = $petition->getNbsoutiens() - 1;
        }

        $petition->setNbsoutiens($nbsoutiens);
        $em->persist($petition);
        $em->flush();
    }

    /**
     * Recherche les Petition en fonction du formulaire
     * @return void 
     */
    public function deleteSoutienPetition($identifiant, $em)
    {

        $query = $em->createQuery(
            'DELETE 
               App\Entity\SoutienPetition s 
             WHERE 
               s.id = :Id'
        )
            ->setParameter("Id", $identifiant);
        $query->execute();
    }

    /**
     * Returns number of Petitions
     * @return void 
     */
    public function getTotalPetitionsSoutenues(User $user)
    {
        $query = $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->where('s.user = :user')
            ->setParameter('user', $user->getId());

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns all Petitions per page
     * @return void 
     */
    public function getPaginatedUserPetitionsSoutenues($page, $limit, User $user, $em)
    {
        //select * from petitions as p JOIN soutien_petition as s ON p.id = s.petition_id where s.user_id = '13a1ef79-312a-4369-8f0f-e5a74b72714f'
        $query = $em->createQuery("SELECT p FROM App\Entity\Petition p JOIN App\Entity\SoutienPetition s WITH s.petition = p.id WHERE s.user = :id");
        $query->setParameter('id', $user)
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
        return $query->getResult();
    }

    // /**
    //  * @return SoutienPetition[] Returns an array of SoutienPetition objects
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
    public function findOneBySomeField($value): ?SoutienPetition
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
