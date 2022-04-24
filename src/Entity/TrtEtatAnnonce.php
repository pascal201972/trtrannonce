<?php

namespace App\Entity;

use App\Repository\TrtEtatAnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrtEtatAnnonceRepository::class)]
class TrtEtatAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $titre;

    #[ORM\OneToMany(mappedBy: 'etat', targetEntity: TrtAnnonce::class)]
    private $trtAnnonces;

    public function __construct()
    {
        $this->trtAnnonces = new ArrayCollection();
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
     * @return Collection<int, TrtAnnonce>
     */
    public function getTrtAnnonces(): Collection
    {
        return $this->trtAnnonces;
    }

    public function addTrtAnnonce(TrtAnnonce $trtAnnonce): self
    {
        if (!$this->trtAnnonces->contains($trtAnnonce)) {
            $this->trtAnnonces[] = $trtAnnonce;
            $trtAnnonce->setEtat($this);
        }

        return $this;
    }

    public function removeTrtAnnonce(TrtAnnonce $trtAnnonce): self
    {
        if ($this->trtAnnonces->removeElement($trtAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($trtAnnonce->getEtat() === $this) {
                $trtAnnonce->setEtat(null);
            }
        }

        return $this;
    }
}
