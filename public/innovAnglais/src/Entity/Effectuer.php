<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EffectuerRepository")
 */
class Effectuer
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
    private $Resultat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Test", inversedBy="effectuers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $test;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Date", inversedBy="effectuers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="effectuers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResultat(): ?string
    {
        return $this->Resultat;
    }

    public function setResultat(string $Resultat): self
    {
        $this->Resultat = $Resultat;

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

    public function getDate(): ?Date
    {
        return $this->Date;
    }

    public function setDate(?Date $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
