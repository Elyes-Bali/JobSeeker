<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
//______________________________________________________________________________________________________________________
    #[ORM\Column(length: 255)]
    private ?string $nom = null;
//______________________________________________________________________________________________________________________
    #[ORM\Column(length: 255)]
    private ?string $prenom = null;
//______________________________________________________________________________________________________________________
    #[ORM\Column]
    private ?int $telephone = null;
//______________________________________________________________________________________________________________________
    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $created_at;
//______________________________________________________________________________________________________________________
    #[ORM\Column(length: 180)]
    private ?string $email = null;
//______________________________________________________________________________________________________________________
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];
//______________________________________________________________________________________________________________________
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
//______________________________________________________________________________________________________________________
    /**
     * @param $created_at
     */
    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }
//______________________________________________________________________________________________________________________


    public function getId(): ?int
    {
        return $this->id;
    }
//______________________________________________________________________________________________________________________
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }
//______________________________________________________________________________________________________________________
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }
//______________________________________________________________________________________________________________________
    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): void
    {
        $this->telephone = $telephone;
    }
//______________________________________________________________________________________________________________________
    /**
     * @return mixed
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }
//______________________________________________________________________________________________________________________
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
//______________________________________________________________________________________________________________________
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
//______________________________________________________________________________________________________________________
    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
    //        $roles = $this->roles;
        return array_unique(array_merge($this->roles));

    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
//______________________________________________________________________________________________________________________
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
//______________________________________________________________________________________________________________________
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}


