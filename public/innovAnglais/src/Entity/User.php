<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json", length=180)
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastNameUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstNameUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Effectuer", mappedBy="user")
     */
    private $effectuers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprise", inversedBy="user")
     */
    private $entreprise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Payer", mappedBy="utilisateur")
     */
    private $payers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NiveauUser", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $n_mdp;

    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

    public function __construct() {
        $this->payers = new ArrayCollection();
        $this->effectuers = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setUsername(string $username): self {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): ?array {
        return $this->roles;
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt() {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastNameUser(): ?string {
        return $this->lastNameUser;
    }

    public function setLastNameUser(string $lastNameUser): self {
        $this->lastNameUser = $lastNameUser;

        return $this;
    }

    public function getFirstNameUser(): ?string {
        return $this->firstNameUser;
    }

    public function setFirstNameUser(string $firstNameUser): self {
        $this->firstNameUser = $firstNameUser;

        return $this;
    }

    public function getAge(): ?int {
        return $this->age;
    }

    public function setAge(int $age): self {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection|Effectuer[]
     */
    public function getEffectuers(): self {
        return $this->effectuers;
    }

    public function addEffectuer(Effectuer $effectuer): self {
        if (!$this->effectuers->contains($effectuer)) {
            $this->effectuers[] = $effectuer;
            $effectuer->setUser($this);
        }

        return $this;
    }

    public function removeEffectuer(Effectuer $effectuer): self {
        if ($this->effectuers->contains($effectuer)) {
            $this->effectuers->removeElement($effectuer);
            // set the owning side to null (unless already changed)
            if ($effectuer->getUser() === $this) {
                $effectuer->setUser(null);
            }
        }

        return $this;
    }

    public function getEntreprise(): ?Entreprise {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * @return Collection|Payer[]
     */
    public function getPayers(): Collection {
        return $this->payers;
    }

    public function addPayer(Payer $payer): self {
        if (!$this->payers->contains($payer)) {
            $this->payers[] = $payer;
            $payer->setUtilisateur($this);
        }

        return $this;
    }

    public function removePayer(Payer $payer): self {
        if ($this->payers->contains($payer)) {
            $this->payers->removeElement($payer);
            // set the owning side to null (unless already changed)
            if ($payer->getUtilisateur() === $this) {
                $payer->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getNiveau(): ?NiveauUser {
        return $this->niveau;
    }

    public function setNiveau(?NiveauUser $niveau): self {
        $this->niveau = $niveau;

        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }

    public function getNMdp(): ?int {
        return $this->n_mdp;
    }

    public function setNMdp(?int $n_mdp): self {
        $this->n_mdp = $n_mdp;

        return $this;
    }

    /**
     * @return string
     */
    public function getResetToken(): string {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void {
        $this->resetToken = $resetToken;
    }

}
