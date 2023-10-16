<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DateRepository")
 */
class Date
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Effectuer", mappedBy="Date")
     */
    private $effectuers;

    public function __construct()
    {
        $this->effectuers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
            $effectuer->setDate($this);
        }

        return $this;
    }

    public function removeEffectuer(Effectuer $effectuer): self
    {
        if ($this->effectuers->contains($effectuer)) {
            $this->effectuers->removeElement($effectuer);
            // set the owning side to null (unless already changed)
            if ($effectuer->getDate() === $this) {
                $effectuer->setDate(null);
            }
        }

        return $this;
    }
}
