<?php

namespace App\Entity;

use App\Repository\TrtUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: TrtUserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class TrtUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $valider;

    #[ORM\Column(type: 'boolean')]
    private $profil;

    #[ORM\OneToOne(mappedBy: 'idUser', targetEntity: TrtProfilrecruteur::class, cascade: ['persist', 'remove'])]
    private $trtProfilrecruteur;

    #[ORM\OneToOne(mappedBy: 'idUser', targetEntity: TrtProfilcandidat::class, cascade: ['persist', 'remove'])]
    private $trtProfilcandidat;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getValider(): ?bool
    {
        return $this->valider;
    }

    public function setValider(bool $valider): self
    {
        $this->valider = $valider;

        return $this;
    }

    public function getProfil(): ?bool
    {
        return $this->profil;
    }

    public function setProfil(bool $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getTrtProfilrecruteur(): ?TrtProfilrecruteur
    {
        return $this->trtProfilrecruteur;
    }

    public function setTrtProfilrecruteur(?TrtProfilrecruteur $trtProfilrecruteur): self
    {
        // unset the owning side of the relation if necessary
        if ($trtProfilrecruteur === null && $this->trtProfilrecruteur !== null) {
            $this->trtProfilrecruteur->setIdUser(null);
        }

        // set the owning side of the relation if necessary
        if ($trtProfilrecruteur !== null && $trtProfilrecruteur->getIdUser() !== $this) {
            $trtProfilrecruteur->setIdUser($this);
        }

        $this->trtProfilrecruteur = $trtProfilrecruteur;

        return $this;
    }

    public function getTrtProfilcandidat(): ?TrtProfilcandidat
    {
        return $this->trtProfilcandidat;
    }

    public function setTrtProfilcandidat(?TrtProfilcandidat $trtProfilcandidat): self
    {
        // unset the owning side of the relation if necessary
        if ($trtProfilcandidat === null && $this->trtProfilcandidat !== null) {
            $this->trtProfilcandidat->setIdUser(null);
        }

        // set the owning side of the relation if necessary
        if ($trtProfilcandidat !== null && $trtProfilcandidat->getIdUser() !== $this) {
            $trtProfilcandidat->setIdUser($this);
        }

        $this->trtProfilcandidat = $trtProfilcandidat;

        return $this;
    }
}
