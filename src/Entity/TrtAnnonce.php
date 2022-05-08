<?php

namespace App\Entity;

use App\Repository\TrtAnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrtAnnonceRepository::class)]
class TrtAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'string', length: 50)]
    private $horaire;

    #[ORM\Column(type: 'integer')]
    private $salaireAnnuel;

    #[ORM\Column(type: 'boolean')]
    private $valider;

    #[ORM\Column(type: 'boolean')]
    private $complet;

    #[ORM\ManyToOne(targetEntity: TrtProfessions::class, inversedBy: 'annonce')]
    private $profession;

    #[ORM\ManyToOne(targetEntity: TrtExperiences::class, inversedBy: 'annonce')]
    private $experience;

    #[ORM\ManyToOne(targetEntity: TrtContrat::class, inversedBy: 'annonce')]
    private $contrat;

    #[ORM\ManyToOne(targetEntity: TrtProfilrecruteur::class, inversedBy: 'annonce')]
    private $recruteur;

    #[ORM\ManyToOne(targetEntity: TrtEtatAnnonce::class, inversedBy: 'trtAnnonces')]
    private $etat;

    #[ORM\Column(type: 'string', length: 50)]
    private $ref;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHoraire(): ?string
    {
        return $this->horaire;
    }

    public function setHoraire(string $horaire): self
    {
        $this->horaire = $horaire;

        return $this;
    }

    public function getSalaireAnnuel(): ?int
    {
        return $this->salaireAnnuel;
    }

    public function setSalaireAnnuel(int $salaireAnnuel): self
    {
        $this->salaireAnnuel = $salaireAnnuel;

        return $this;
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

    public function getComplet(): ?bool
    {
        return $this->complet;
    }

    public function setComplet(bool $complet): self
    {
        $this->complet = $complet;

        return $this;
    }

    public function getProfession(): ?TrtProfessions
    {
        return $this->profession;
    }

    public function setProfession(?TrtProfessions $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getExperience(): ?TrtExperiences
    {
        return $this->experience;
    }

    public function setExperience(?TrtExperiences $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getContrat(): ?TrtContrat
    {
        return $this->contrat;
    }

    public function setContrat(?TrtContrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getRecruteur(): ?TrtProfilrecruteur
    {
        return $this->recruteur;
    }

    public function setRecruteur(?TrtProfilrecruteur $recruteur): self
    {
        $this->recruteur = $recruteur;

        return $this;
    }

    public function getEtat(): ?TrtEtatAnnonce
    {
        return $this->etat;
    }

    public function setEtat(?TrtEtatAnnonce $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }
}
