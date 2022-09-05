<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StructureRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: StructureRepository::class)]
class Structure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\OneToOne(inversedBy: 'structure', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    // #[ORM\ManyToOne(inversedBy: 'structures')]
    // #[ORM\JoinColumn(nullable: false)]
    // private Partner $partner;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Partner', inversedBy: 'structures')]
    #[ORM\JoinColumn(nullable: false)]
    private Partner $partner;

    #[ORM\Column(length: 255)]
    private ?string $postalAdress;

    #[ORM\ManyToMany(targetEntity: Permissions::class, inversedBy: 'structures', cascade: ['persist'])]
    private Collection $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

    // #[ORM\Column]
    // private bool $isPlanning;

    // #[ORM\Column]
    // private bool $isNewsletter;

    // #[ORM\Column]
    // private bool $isBoissons;

    // #[ORM\Column]
    // private bool $isSms;

    // #[ORM\Column]
    // private bool $isConcours;

    // #[ORM\ManyToMany(targetEntity: Permissions::class, inversedBy: 'structures', cascade: ['persist'])]
    // private Collection $permissions;

    // public function __construct()
    // {
    //     $this->permissions = new ArrayCollection();
    // }

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

    public function getPartner(): Partner
    {
        return $this->partner;
    }

    public function setPartner(Partner $partner): self
    {
        $this->partner = $partner;

        return $this;
    }

    public function getPostalAdress(): ?string
    {
        return $this->postalAdress;
    }

    public function setPostalAdress(string $postalAdress): self
    {
        $this->postalAdress = $postalAdress;

        return $this;
    }

    // public function isIsPlanning(): ?bool
    // {
    //     return $this->isPlanning;
    // }

    // public function setIsPlanning(bool $isPlanning): self
    // {
    //     $this->isPlanning = $isPlanning;

    //     return $this;
    // }

    // public function isIsNewsletter(): ?bool
    // {
    //     return $this->isNewsletter;
    // }

    // public function setIsNewsletter(bool $isNewsletter): self
    // {
    //     $this->isNewsletter = $isNewsletter;

    //     return $this;
    // }

    // public function isIsBoissons(): ?bool
    // {
    //     return $this->isBoissons;
    // }

    // public function setIsBoissons(bool $isBoissons): self
    // {
    //     $this->isBoissons = $isBoissons;

    //     return $this;
    // }

    // public function isIsSms(): ?bool
    // {
    //     return $this->isSms;
    // }

    // public function setIsSms(bool $isSms): self
    // {
    //     $this->isSms = $isSms;

    //     return $this;
    // }

    // public function isIsConcours(): ?bool
    // {
    //     return $this->isConcours;
    // }

    // public function setIsConcours(bool $isConcours): self
    // {
    //     $this->isConcours = $isConcours;

    //     return $this;
    // }

    public function __toString()
    {
        return $this->name;
    }

    
    // public function getPermissions()
    // {
    //     return $this->permissions;
    // }

    // public function addPermission(Permissions $permission): self
    // {
    //     if (!$this->permissions->contains($permission)) {
    //         $this->permissions->add($permission);
    //     }

    //     return $this;
    // }

    // public function removePermission(Permissions $permission): self
    // {
    //     $this->permissions->removeElement($permission);

    //     return $this;
    // }

    /**
     * @return Collection<int, Permissions>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permissions $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }

        return $this;
    }

    public function removePermission(Permissions $permission): self
    {
        $this->permissions->removeElement($permission);

        return $this;
    }
    
}
