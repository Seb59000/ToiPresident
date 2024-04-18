<?php

namespace App\Repository;

use App\Entity\Petition;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Petition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Petition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Petition[]    findAll()
 * @method Petition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Petition::class);
    }

    /**
     * Returns all Petitions per page
     * @return void 
     */
    public function getPaginatedRecentPetitions($page, $limit)
    {
        $query = $this->createQueryBuilder('p');
        $query->orderBy('p.date')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    /**
     * Returns all Petitions per page
     * @return void 
     */
    public function getPaginatedRecentPetitionsCategorie($page, $limit, $categorie)
    {
        $query = $this->createQueryBuilder('p');
        $query->orderBy('p.date')
            ->where('p.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    /**
     * Returns all Petitions per page
     * @return void 
     */
    public function getPaginatedBestPetitions($page, $limit)
    {
        $query = $this->createQueryBuilder('p');
        $query->orderBy('p.nbsoutiens', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    /**
     * Returns all Petitions per page
     * @return void 
     */
    public function getPaginatedBestPetitionsCategorie($page, $limit, $categorie)
    {
        $query = $this->createQueryBuilder('p');
        $query->orderBy('p.nbsoutiens', 'DESC')
            ->where('p.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    /**
     * Returns all Petitions per page
     * @return void 
     */
    public function getPaginatedUserPetitions($page, $limit, User $user)
    {
        $query = $this->createQueryBuilder('p');
        $query->orderBy('p.nbsoutiens', 'DESC')
            ->where('p.instigateur = :instigateur')
            ->setParameter('instigateur', $user->getId())
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    /**
     * Returns all Petitions per page
     * @return void 
     */
    public function getPaginatedPetitionsCandidat($page, $limit, $candidat, $em)
    {
        $query = $em->createQuery("SELECT p FROM App\Entity\Petition p JOIN App\Entity\SoutienCandidat s WITH s.petition = p.id WHERE s.candidat = :candidat")
            ->setParameter('candidat', $candidat)
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);

        return $query->getResult();
    }

    /**
     * Returns all Petitions per page
     * @return void 
     */
    public function getPaginatedPetitionsCandidatParCategorie($page, $limit, $candidat, $categorie, $em)
    {
        $query = $em->createQuery("SELECT p FROM App\Entity\Petition p JOIN App\Entity\SoutienCandidat s WITH s.petition = p.id WHERE s.candidat = :candidat AND p.categorie = :categorie")
            ->setParameter('candidat', $candidat)
            ->setParameter('categorie', $categorie)
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);

        return $query->getResult();
    }

    /**
     * Returns number of Petitions
     * @return void 
     */
    public function getTotalPetitions()
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)');
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns number of Petitions
     * @return void 
     */
    public function getTotalPetitionsUser(User $user)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.instigateur = :instigateur')
            ->setParameter('instigateur', $user->getId());

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns number of Petitions
     * @return void 
     */
    public function getTotalPetitionsCategorie($categorie)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.categorie = :categorie')
            ->setParameter('categorie', $categorie);

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns number of Petitions soutenu par candidat
     * @return void 
     */
    public function getTotalPetitionsCandidat($candidat, $em)
    {
        $query = $em->createQuery("SELECT COUNT(p.id) FROM App\Entity\Petition p JOIN App\Entity\SoutienCandidat s WITH s.petition = p.id WHERE s.candidat = :candidat")
            ->setParameter('candidat', $candidat);

        return $query->getSingleScalarResult();
    }

    /**
     * Returns number of Petitions soutenu par candidat
     * @return void 
     */
    public function getTotalPetitionsCandidatParCategorie($candidat, $categorie, $em)
    {
        $query = $em->createQuery("SELECT COUNT(p.id) FROM App\Entity\Petition p JOIN App\Entity\SoutienCandidat s WITH s.petition = p.id WHERE s.candidat = :candidat AND p.categorie = :categorie")
            ->setParameter('candidat', $candidat)
            ->setParameter('categorie', $categorie);

        return $query->getSingleScalarResult();
    }

    /**
     * Recherche les annonces en fonction du formulaire
     * @return void 
     */
    public function search($mots = null)
    {
        $query = $this->createQueryBuilder('p');
        if ($mots != null) {
            // (:mots boolean)>0' vient de doc officielle verifie si il y a un mot
            $query->andWhere('MATCH_AGAINST(p.titre, p.contenu) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Petition[] Returns an array of Petition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Petition
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
