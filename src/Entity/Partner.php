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


    #[ORM\OneToMany(targetEntity: 'App\Entity\Structure', mappedBy: 'partner', orphanRemoval: true)]
    // private Collection $structures;
    private $structures;


    #[ORM\ManyToMany(targetEntity: Permissions::class, inversedBy: 'partners', cascade: ['persist'])]
    private Collection $permissions;

    public function __construct()
    {
        $this->structures = new ArrayCollection();
        $this->permissions = new ArrayCollection();
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

   
    public function getStructures()
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


    public function __toString()
    {
        return $this->name;
    }

    
    public function getPermissions()
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
