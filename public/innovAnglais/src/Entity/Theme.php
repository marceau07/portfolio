<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ThemeRepository")
 */
class Theme
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
     * @ORM\Column(type="string", length=255)
     */
    private $icone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Test", mappedBy="theme")
     */
    private $posseder;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vocabulaire", mappedBy="theme")
     */
    private $vocabulaires;




    public function __construct()
    {
        $this->posseder = new ArrayCollection();
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
  
    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(string $icone): self
    {
        $this->icone = $icone;

        return $this;
    }
    
    /**
     * @return Collection|Test[]
     */
    public function getPosseder(): Collection
    {
        return $this->posseder;
    }

    public function addPosseder(Test $posseder): self
    {
        if (!$this->posseder->contains($posseder)) {
            $this->posseder[] = $posseder;
            $posseder->setTheme($this);
        }

        return $this;
    }

    public function removePosseder(Test $posseder): self
    {
        if ($this->posseder->contains($posseder)) {
            $this->posseder->removeElement($posseder);
            // set the owning side to null (unless already changed)
            if ($posseder->getTheme() === $this) {
                $posseder->setTheme(null);
            }
        }

        return $this;
    }

    public function getTest(): ?Test
    {
        return $this->test;
    }

    public function setTest(?Test $test): self
    {
        $this->test = $test;

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
            $vocabulaire->setTheme($this);
        }

        return $this;
    }

    public function removeVocabulaire(Vocabulaire $vocabulaire): self
    {
        if ($this->vocabulaires->contains($vocabulaire)) {
            $this->vocabulaires->removeElement($vocabulaire);
            // set the owning side to null (unless already changed)
            if ($vocabulaire->getTheme() === $this) {
                $vocabulaire->setTheme(null);
            }
        }

        return $this;
    }


}
