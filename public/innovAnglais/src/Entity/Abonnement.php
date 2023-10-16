<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AbonnementRepository")
 */
class Abonnement
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
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbFois;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Payer", mappedBy="abonnement")
     */
    private $payers;

    public function __construct()
    {
        $this->payers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNbFois(): ?int
    {
        return $this->nbFois;
    }

    public function setNbFois(int $nbFois): self
    {
        $this->nbFois = $nbFois;

        return $this;
    }

    /**
     * @return Collection|Payer[]
     */
    public function getPayers(): Collection
    {
        return $this->payers;
    }

    public function addPayer(Payer $payer): self
    {
        if (!$this->payers->contains($payer)) {
            $this->payers[] = $payer;
            $payer->setAbonnement($this);
        }

        return $this;
    }

    public function removePayer(Payer $payer): self
    {
        if ($this->payers->contains($payer)) {
            $this->payers->removeElement($payer);
            // set the owning side to null (unless already changed)
            if ($payer->getAbonnement() === $this) {
                $payer->setAbonnement(null);
            }
        }

        return $this;
    }
}
