<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = true;

    #[ORM\Column(nullable: true)]
    private ?bool $isNewsletter = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPlanning = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBoissons = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isSMS = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isConcours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isIsNewsletter(): ?bool
    {
        return $this->isNewsletter;
    }

    public function setIsNewsletter(?bool $isNewsletter): self
    {
        $this->isNewsletter = $isNewsletter;

        return $this;
    }

    public function isIsPlanning(): ?bool
    {
        return $this->isPlanning;
    }

    public function setIsPlanning(?bool $isPlanning): self
    {
        $this->isPlanning = $isPlanning;

        return $this;
    }

    public function isIsBoissons(): ?bool
    {
        return $this->isBoissons;
    }

    public function setIsBoissons(?bool $isBoissons): self
    {
        $this->isBoissons = $isBoissons;

        return $this;
    }

    public function isIsSMS(): ?bool
    {
        return $this->isSMS;
    }

    public function setIsSMS(?bool $isSMS): self
    {
        $this->isSMS = $isSMS;

        return $this;
    }

    public function isIsConcours(): ?bool
    {
        return $this->isConcours;
    }

    public function setIsConcours(?bool $isConcours): self
    {
        $this->isConcours = $isConcours;

        return $this;
    }
}
