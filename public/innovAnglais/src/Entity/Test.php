<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
 */
class Test
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Theme", inversedBy="posseder")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Effectuer", mappedBy="test")
     */
    private $effectuers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Niveau", inversedBy="tests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icone;

    public function __construct()
    {
        $this->effectuers = new ArrayCollection();
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


    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getPosseder()
    {
        return $this->posseder;
    }

    public function setPosseder(Theme $posseder): self
    {
        $this->posseder = $posseder;

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

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
     * @return Collection|Effectuer[]
     */
    public function getEffectuers(): Collection
    {
        return $this->effectuers;
    }

    public function addEffectuer(Effectuer $effectuer): self
    {
        if (!$this->effectuers->contains($effectuer)) {
            $this->effectuers[] = $effectuer;
            $effectuer->setTest($this);
        }

        return $this;
    }

    public function removeEffectuer(Effectuer $effectuer): self
    {
        if ($this->effectuers->contains($effectuer)) {
            $this->effectuers->removeElement($effectuer);
            // set the owning side to null (unless already changed)
            if ($effectuer->getTest() === $this) {
                $effectuer->setTest(null);
            }
        }

        return $this;
    }
}
