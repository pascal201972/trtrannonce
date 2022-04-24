<?php

namespace App\Entity;

use App\Repository\TrtExperiencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrtExperiencesRepository::class)]
class TrtExperiences
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $titre;

    #[ORM\OneToMany(mappedBy: 'experience', targetEntity: TrtProfilcandidat::class)]
    private $trtProfilcandidats;

    #[ORM\OneToMany(mappedBy: 'experience', targetEntity: TrtAnnonce::class)]
    private $annonce;

    public function __construct()
    {
        $this->trtProfilcandidats = new ArrayCollection();
        $this->annonce = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection<int, TrtProfilcandidat>
     */
    public function getTrtProfilcandidats(): Collection
    {
        return $this->trtProfilcandidats;
    }

    public function addTrtProfilcandidat(TrtProfilcandidat $trtProfilcandidat): self
    {
        if (!$this->trtProfilcandidats->contains($trtProfilcandidat)) {
            $this->trtProfilcandidats[] = $trtProfilcandidat;
            $trtProfilcandidat->setExperience($this);
        }

        return $this;
    }

    public function removeTrtProfilcandidat(TrtProfilcandidat $trtProfilcandidat): self
    {
        if ($this->trtProfilcandidats->removeElement($trtProfilcandidat)) {
            // set the owning side to null (unless already changed)
            if ($trtProfilcandidat->getExperience() === $this) {
                $trtProfilcandidat->setExperience(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TrtAnnonce>
     */
    public function getAnnonce(): Collection
    {
        return $this->annonce;
    }

    public function addAnnonce(TrtAnnonce $annonce): self
    {
        if (!$this->annonce->contains($annonce)) {
            $this->annonce[] = $annonce;
            $annonce->setExperience($this);
        }

        return $this;
    }

    public function removeAnnonce(TrtAnnonce $annonce): self
    {
        if ($this->annonce->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getExperience() === $this) {
                $annonce->setExperience(null);
            }
        }

        return $this;
    }
}
