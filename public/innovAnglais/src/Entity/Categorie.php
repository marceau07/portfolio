<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
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
     * @ORM\OneToMany(targetEntity="App\Entity\Vocabulaire", mappedBy="categorie")
     */
    private $vocabulaires;


    public function __construct()
    {
        $this->associer = new ArrayCollection();
        $this->vocabulaires = new ArrayCollection();
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

    /**
     * @return Collection|Vocabulaire[]
     */
    public function getAssocier(): Collection
    {
        return $this->associer;
    }

    public function addAssocier(Vocabulaire $associer)
    {
        if (!$this->associer->contains($associer)) {
            $this->associer[] = $associer;
            $associer->setCategorie($this);
        }

        return $this;
    }

    public function removeAssocier(Vocabulaire $associer): self
    {
        if ($this->associer->contains($associer)) {
            $this->associer->removeElement($associer);
            // set the owning side to null (unless already changed)
            if ($associer->getCategorie() === $this) {
                $associer->setCategorie(null);
            }
        }

        return $this;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setVocabulaire( $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;

        // set the owning side of the relation if necessary
        if ($vocabulaire->getAssocier() !== $this) {
            $vocabulaire->setAssocier($this);
        }

        return $this;
    }

    /**
     * @return Collection|Vocabulaire[]
     */
    public function getVocabulaires(): Collection
    {
        return $this->vocabulaires;
    }

    public function addVocabulaire(Vocabulaire $vocabulaire): self
    {
        if (!$this->vocabulaires->contains($vocabulaire)) {
            $this->vocabulaires[] = $vocabulaire;
            $vocabulaire->setCategorie($this);
        }

        return $this;
    }

    public function removeVocabulaire(Vocabulaire $vocabulaire): self
    {
        if ($this->vocabulaires->contains($vocabulaire)) {
            $this->vocabulaires->removeElement($vocabulaire);
            // set the owning side to null (unless already changed)
            if ($vocabulaire->getCategorie() === $this) {
                $vocabulaire->setCategorie(null);
            }
        }

        return $this;
    }
}
