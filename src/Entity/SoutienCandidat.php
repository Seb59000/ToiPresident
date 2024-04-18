<?php

namespace App\Entity;

use App\Repository\SoutienCandidatRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=SoutienCandidatRepository::class)
 */
class SoutienCandidat
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
     * @ORM\ManyToOne(targetEntity=Petition::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $petition;

    /**
     * @ORM\ManyToOne(targetEntity=Candidat::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidat;

    public function getId()
    {
        return $this->id;
    }

    public function getPetition(): ?Petition
    {
        return $this->petition;
    }

    public function setPetition(?Petition $petition): self
    {
        $this->petition = $petition;

        return $this;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }
}
