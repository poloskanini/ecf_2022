<?php

namespace App\Entity;

use App\Repository\PartenaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartenaireRepository::class)]
class Partenaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?bool $planning = null;

    #[ORM\Column(nullable: true)]
    private ?bool $newsletter = null;

    #[ORM\Column(nullable: true)]
    private ?bool $boissons = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sms = null;

    #[ORM\Column(nullable: true)]
    private ?bool $promotions = null;

    #[ORM\Column(nullable: true)]
    private ?bool $concours = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function isPlanning(): ?bool
    {
        return $this->planning;
    }

    public function setPlanning(?bool $planning): self
    {
        $this->planning = $planning;

        return $this;
    }

    public function isNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(?bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function isBoissons(): ?bool
    {
        return $this->boissons;
    }

    public function setBoissons(?bool $boissons): self
    {
        $this->boissons = $boissons;

        return $this;
    }

    public function isSms(): ?bool
    {
        return $this->sms;
    }

    public function setSms(?bool $sms): self
    {
        $this->sms = $sms;

        return $this;
    }

    public function isPromotions(): ?bool
    {
        return $this->promotions;
    }

    public function setPromotions(?bool $promotions): self
    {
        $this->promotions = $promotions;

        return $this;
    }

    public function isConcours(): ?bool
    {
        return $this->concours;
    }

    public function setConcours(?bool $concours): self
    {
        $this->concours = $concours;

        return $this;
    }
}
