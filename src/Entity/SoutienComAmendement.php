<?php

namespace App\Entity;

use App\Repository\SoutienComAmendementRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=SoutienComAmendementRepository::class)
 */
class SoutienComAmendement
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
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=ComAmendement::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $comAmendement;

    public function getId()
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getComAmendement(): ?ComAmendement
    {
        return $this->comAmendement;
    }

    public function setComAmendement(?ComAmendement $comAmendement): self
    {
        $this->comAmendement = $comAmendement;

        return $this;
    }
}
