<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre;

    #[ORM\Column(type: 'text')]
    private ?string $description;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $date;

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $priorite = null; // Urgent, Moyen, Bas

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $categorie = 'Autres'; // Valeur par défaut

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $statut = 'Ouvert'; // Valeur par défaut


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    // Relation avec les réponses
    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: 'reclamation', orphanRemoval: true)]
    private Collection $reponses;

    public function __construct()
    {
        // Initialisation de la collection de réponses
        $this->responses = new ArrayCollection();
    }
    // Getters and setters


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPriorite(): ?string
    {
        return $this->priorite;
    }

    public function setPriorite(string $priorite): self
    {
        $this->priorite = $priorite;
        return $this;
    }

    public function getCategorie(): ?string
{
    return $this->categorie;
}

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
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

    // Méthodes pour gérer les réponses
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setReclamation($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // Si la réponse est supprimée, on dissocie la réclamation
            if ($reponse->getReclamation() === $this) {
                $reponse->setReclamation(null);
            }
        }

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
}
