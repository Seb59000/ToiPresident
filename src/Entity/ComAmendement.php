<?php

namespace App\Entity;

use App\Repository\ComAmendementRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=ComAmendementRepository::class)
 */
class ComAmendement
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
     * @ORM\ManyToOne(targetEntity=Amendement::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $amendement;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbsoutiens;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    public function __construct()
    {
        $this->datetime = new \DateTime('now');
        $this->setNbsoutiens(1);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAmendement(): ?Amendement
    {
        return $this->amendement;
    }

    public function setAmendement(?Amendement $amendement): self
    {
        $this->amendement = $amendement;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }
}
