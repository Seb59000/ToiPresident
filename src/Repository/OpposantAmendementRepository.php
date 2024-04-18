<?php

namespace App\Repository;

use App\Entity\OpposantAmendement;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OpposantAmendement|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpposantAmendement|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpposantAmendement[]    findAll()
 * @method OpposantAmendement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpposantAmendementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpposantAmendement::class);
    }

    /**
     * Recherche les OpposantAmendement en fonction du formulaire
     * @return void 
     */
    public function searchOpposantAmendement(User $user, $amendementid, $em)
    {
        $query = $em->createQuery('SELECT s FROM App\Entity\OpposantAmendement s WHERE s.user = :userid AND s.amendement = :amendementid');
        $query->setParameters(array(
            'userid' => $user->getId(),
            'amendementid' => $amendementid,
        ));
        return $query->getResult();
    }

    /**
     * Recherche les OpposantAmendement en fonction du formulaire
     * @return void 
     */
    public function createOpposantAmendement($userid, $petition, $em)
    {
        // on ajoute un opposant pour la petition
        $opposition = new OpposantAmendement();
        $opposition->setUser($userid);
        $opposition->setAmendement($petition);
        $em->persist($opposition);
        $em->flush();

        // MAJ nb opposants dans entité petition
        $this->MAJNbOpposant(true, $petition, $em);
    }

    public function MAJNbOpposant($incremente, $petition, $em)
    {
        if ($incremente) {
            $nbopposants = $petition->getNbopposant() + 1;
        } else {
            $nbopposants = $petition->getNbopposant() - 1;
        }
        $petition->setNbopposant($nbopposants);
        $em->persist($petition);
        $em->flush();
    }

    /**
     * Recherche les OpposantAmendement en fonction du formulaire
     * @return void 
     */
    public function deleteOpposantAmendement($identifiant, $em)
    {

        $query = $em->createQuery(
            'DELETE 
               App\Entity\OpposantAmendement o 
             WHERE 
               o.id = :Id'
        )
            ->setParameter("Id", $identifiant);
        $query->execute();
    }

    // /**
    //  * @return OpposantAmendement[] Returns an array of OpposantAmendement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OpposantAmendement
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
