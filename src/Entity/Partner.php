<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartnerRepository::class)]
class Partner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\OneToOne(inversedBy: 'partner', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $name;

    // #[ORM\OneToMany(mappedBy: 'partner', targetEntity: Structure::class)]
    // private Collection $structures;

    #[ORM\OneToMany(targetEntity: 'App\Entity\Structure', mappedBy: 'partner')]
    // private Collection $structures;
    private $structures;

    #[ORM\Column]
    private bool $isPlanning;

    #[ORM\Column]
    private bool $isNewsletter;

    #[ORM\Column]
    private bool $isBoissons;

    #[ORM\Column]
    private bool $isSms;

    #[ORM\Column]
    private bool $isConcours;

    public function __construct()
    {
        $this->structures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
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

    /**
     * @return Collection<int, Structure>
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures->add($structure);
            $structure->setPartner($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structures->removeElement($structure)) {
            // set the owning side to null (unless already changed)
            if ($structure->getPartner() === $this) {
                $structure->setPartner(null);
            }
        }

        return $this;
    }



    public function isIsPlanning(): ?bool
    {
        return $this->isPlanning;
    }

    public function setIsPlanning(bool $isPlanning): self
    {
        $this->isPlanning = $isPlanning;

        return $this;
    }

    public function isIsNewsletter(): ?bool
    {
        return $this->isNewsletter;
    }

    public function setIsNewsletter(bool $isNewsletter): self
    {
        $this->isNewsletter = $isNewsletter;

        return $this;
    }

    public function isIsBoissons(): ?bool
    {
        return $this->isBoissons;
    }

    public function setIsBoissons(bool $isBoissons): self
    {
        $this->isBoissons = $isBoissons;

        return $this;
    }

    public function isIsSms(): ?bool
    {
        return $this->isSms;
    }

    public function setIsSms(bool $isSms): self
    {
        $this->isSms = $isSms;

        return $this;
    }

    public function isIsConcours(): ?bool
    {
        return $this->isConcours;
    }

    public function setIsConcours(bool $isConcours): self
    {
        $this->isConcours = $isConcours;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
