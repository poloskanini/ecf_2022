<?php

namespace App\Entity;

use App\Repository\PermissionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionsRepository::class)]
class Permissions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isPlanning = null;

    #[ORM\Column]
    private ?bool $isNewsletter = null;

    #[ORM\Column]
    private ?bool $isBoissons = null;

    #[ORM\Column]
    private ?bool $isSms = null;

    #[ORM\Column]
    private ?bool $isConcours = null;

    #[ORM\ManyToMany(targetEntity: Partner::class, mappedBy: 'permissions')]
    private Collection $partners;

    #[ORM\ManyToMany(targetEntity: Structure::class, mappedBy: 'permissions')]
    private Collection $structures;

    // #[ORM\ManyToMany(targetEntity: Structure::class, inversedBy: 'permissions')]
    // private Collection $structures;

    public function __construct()
    {
        $this->partners = new ArrayCollection();
        $this->structures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Partner>
     */
    public function getPartners(): Collection
    {
        return $this->partners;
    }

    public function addPartner(Partner $partner): self
    {
        if (!$this->partners->contains($partner)) {
            $this->partners->add($partner);
            $partner->addPermission($this);
        }

        return $this;
    }

    public function removePartner(Partner $partner): self
    {
        if ($this->partners->removeElement($partner)) {
            $partner->removePermission($this);
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, Structure>
    //  */
    // public function getStructures(): Collection
    // {
    //     return $this->structures;
    // }

    // public function addStructure(Structure $structure): self
    // {
    //     if (!$this->structures->contains($structure)) {
    //         $this->structures->add($structure);
    //     }

    //     return $this;
    // }

    // public function removeStructure(Structure $structure): self
    // {
    //     $this->structures->removeElement($structure);

    //     return $this;
    // }

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
            $structure->addPermission($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structures->removeElement($structure)) {
            $structure->removePermission($this);
        }

        return $this;
    }

}
