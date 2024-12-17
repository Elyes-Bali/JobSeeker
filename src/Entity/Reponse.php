<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

//------------------------------------------------------------------------

    public function __construct()
    {
        $this->dateReponse = new \DateTime(); // Définit la date actuelle par défaut
        $this->lue = false; // Par défaut, la réponse est non lue
    }

//------------------------------------------------------------------------
    #[ORM\Column(type: 'text')]
    private ?string $texte = null;

//------------------------------------------------------------------------
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateReponse = null;

//------------------------------------------------------------------------
    #[ORM\ManyToOne(targetEntity:"App\Entity\Reclamation", inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reclamation $reclamation;

//------------------------------------------------------------------------
    #[ORM\Column(type: 'boolean')]
    private bool $lue = false;

//------------------------------------------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

//------------------------------------------------------------------------

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

//------------------------------------------------------------------------

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->dateReponse;
    }

    public function setDateReponse(\DateTimeInterface $dateReponse): self
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

//------------------------------------------------------------------------

    public function getLue(): ?bool
    {
        return $this->lue;
    }

    public function setLue(bool $lue): self
    {
        $this->lue = $lue;

        return $this;
    }

//------------------------------------------------------------------------

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }


    //------------------------------------------------------------------------

    public function marquerCommeLue(): self
    {
        $this->lue = true;

        return $this;
    }

    public function marquerCommeNonLue(): self
    {
        $this->lue = false;

        return $this;
    }


}
