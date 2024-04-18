<?php

namespace App\Entity;

use App\Repository\PetitionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=PetitionRepository::class)
 * @ORM\Table(name="petitions", indexes={@ORM\Index(columns={"titre", "contenu"}, flags={"fulltext"})})
 */
class Petition
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $instigateur;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbsoutiens;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbopposants;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->setNbsoutiens(1);
        $this->setNbopposants(0);
    }

    public function __toString()
    {
        return $this->getTitre();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getInstigateur(): ?User
    {
        return $this->instigateur;
    }

    public function setInstigateur(?User $instigateur): self
    {
        $this->instigateur = $instigateur;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getNbsoutiens(): ?int
    {
        return $this->nbsoutiens;
    }

    public function setNbsoutiens(int $nbsoutiens): self
    {
        $this->nbsoutiens = $nbsoutiens;

        return $this;
    }

    public function getNbopposants(): ?int
    {
        return $this->nbopposants;
    }

    public function setNbopposants(int $nbopposants): self
    {
        $this->nbopposants = $nbopposants;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
