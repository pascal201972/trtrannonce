<?php

namespace App\Entity;

use App\Repository\TrtCandidatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrtCandidatureRepository::class)]
class TrtCandidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $annonce;

    #[ORM\Column(type: 'integer')]
    private $profil;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnonce(): ?int
    {
        return $this->annonce;
    }

    public function setAnnonce(int $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function getProfil(): ?int
    {
        return $this->profil;
    }

    public function setProfil(int $profil): self
    {
        $this->profil = $profil;

        return $this;
    }
}
