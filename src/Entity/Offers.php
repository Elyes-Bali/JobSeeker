<?php

namespace App\Entity;

use App\Repository\OffersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffersRepository::class)]
class Offers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $anneesExperience = null;

    #[ORM\Column(length: 255)]
    private ?string $connaissances = null;

    #[ORM\Column(length: 255)]
    private ?string $contrat = null;

    #[ORM\Column(length: 255)]
    private ?string $experienceRequise = null;

    #[ORM\Column(length: 255)]
    private ?string $formation = null;

    #[ORM\Column(length: 255)]
    private ?string $langue = null;

    #[ORM\Column]
    private ?int $nbrRecruter = null;

    #[ORM\Column]
    private ?int $salaire = null;

    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $villeTravail = null;

    #[ORM\Column(type: 'json')]
    private array $userIds = [];

    #[ORM\Column(nullable: true)]
private ?int $selectedApplayerId = null;


#[ORM\Column(type: 'integer')]
private ?int $creatorId = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnneesExperience(): ?int
    {
        return $this->anneesExperience;
    }

    public function setAnneesExperience(int $anneesExperience): static
    {
        $this->anneesExperience = $anneesExperience;

        return $this;
    }

    public function getConnaissances(): ?string
    {
        return $this->connaissances;
    }

    public function setConnaissances(string $connaissances): static
    {
        $this->connaissances = $connaissances;

        return $this;
    }

    public function getContrat(): ?string
    {
        return $this->contrat;
    }

    public function setContrat(string $contrat): static
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getExperienceRequise(): ?string
    {
        return $this->experienceRequise;
    }

    public function setExperienceRequise(string $experienceRequise): static
    {
        $this->experienceRequise = $experienceRequise;

        return $this;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }

    public function setFormation(string $formation): static
    {
        $this->formation = $formation;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }


    public function getNbrRecruter(): ?int
    {
        return $this->nbrRecruter;
    }

    public function setNbrRecruter(int $nbrRecruter): static
    {
        $this->nbrRecruter = $nbrRecruter;

        return $this;
    }

    public function isSalaire(): ?int
    {
        return $this->salaire;
    }

    public function setSalaire(int $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

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

    public function getVilleTravail(): ?string
    {
        return $this->villeTravail;
    }

    public function setVilleTravail(string $villeTravail): static
    {
        $this->villeTravail = $villeTravail;

        return $this;
    }


    public function getUserIds(): array
    {
        return $this->userIds;
    }

    public function setUserIds(array $userIds): self
    {
        $this->userIds = $userIds;

        return $this;
    }
    // Dans App\Entity\Offers
    public function getSalaire(): ?int
    {
        return $this->salaire;
    }
    // public function isActive(): bool
    // {
    //     return $this->isActive;
    // }

    // public function setIsActive(bool $isActive): self
    // {
    //     $this->isActive = $isActive;
    //     return $this;
    // }

    // public function isExpired(): bool
    // {
    //     return $this->isExpired;
    // }


    public function getSelectedApplayerId(): ?int
{
    return $this->selectedApplayerId;
}

public function setSelectedApplayerId(?int $selectedApplayerId): static
{
    $this->selectedApplayerId = $selectedApplayerId;

    return $this;
}

public function getCreatorId(): ?int
{
    return $this->creatorId;
}

public function setCreatorId(int $creatorId): self
{
    $this->creatorId = $creatorId;
    return $this;
}
}