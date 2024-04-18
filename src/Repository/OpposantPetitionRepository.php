<?php

namespace App\Repository;

use App\Entity\OpposantPetition;
use App\Entity\Petition;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OpposantPetition|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpposantPetition|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpposantPetition[]    findAll()
 * @method OpposantPetition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpposantPetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpposantPetition::class);
    }

    // /**
    //  * @return OpposantPetition[] Returns an array of OpposantPetition objects
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

    /**
     * Recherche les annonces en fonction du formulaire
     * @return void 
     */
    public function searchOpposantPetition(User $user, $petitionId, $em)
    {
        $query = $em->createQuery('SELECT s FROM App\Entity\OpposantPetition s WHERE s.user = :userid AND s.petition = :petitionid');
        $query->setParameters(array(
            'userid' => $user->getId(),
            'petitionid' => $petitionId,
        ));
        return $query->getResult();
    }

    /**
     * Recherche les annonces en fonction du formulaire
     * @return void 
     */
    public function createOpposantPetition($userid, $petition, $em)
    {
        // on ajoute un opposant pour la petition
        $opposition = new OpposantPetition();
        $opposition->setUser($userid);
        $opposition->setPetition($petition);
        $em->persist($opposition);
        $em->flush();

        // MAJ nb opposants dans entité petition
        $this->MAJNbOpposant(true, $petition, $em);
    }

    public function MAJNbOpposant($incremente, $petition, $em)
    {
        if ($incremente) {
            $nbopposants = $petition->getNbopposants() + 1;
        } else {
            $nbopposants = $petition->getNbopposants() - 1;
        }
        $petition->setNbopposants($nbopposants);
        $em->persist($petition);
        $em->flush();
    }

    /**
     * Recherche les annonces en fonction du formulaire
     * @return void 
     */
    public function deleteOpposantPetition($identifiant, $em)
    {

        $query = $em->createQuery(
            'DELETE 
               App\Entity\OpposantPetition s 
             WHERE 
               s.id = :Id'
        )
            ->setParameter("Id", $identifiant);
        $query->execute();
    }

    public function findOneBySomeField($user, $petition): ?OpposantPetition
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.petition = :petition')
            ->setParameter('user', $user)
            ->setParameter('petition', $petition)
            ->getQuery()
            ->getResult();
    }
}
