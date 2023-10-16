<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mdp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role_id;

    public function __construct()
    {
        $this->role_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

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

    public function getRoleId(): ?Role
    {
        return $this->role_id;
    }

    public function addRoleId(Role $roleId): self
    {
        if (!$this->role_id->contains($roleId)) {
            $this->role_id[] = $roleId;
            $roleId->setUtilisateur($this);
        }

        return $this;
    }

    public function removeRoleId(Role $roleId): self
    {
        if ($this->role_id->contains($roleId)) {
            $this->role_id->removeElement($roleId);
            // set the owning side to null (unless already changed)
            if ($roleId->getUtilisateur() === $this) {
                $roleId->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function setRoleId(?Role $role_id): self
    {
        $this->role_id = $role_id;

        return $this;
    }
}
