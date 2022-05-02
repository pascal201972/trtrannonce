<?php

namespace App\Entity;

use App\Repository\TrtInitpasswordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrtInitpasswordRepository::class)]
class TrtInitpassword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    #[ORM\Column(type: 'integer')]
    private $expire;

    #[ORM\OneToOne(inversedBy: 'initPassword', targetEntity: TrtUser::class, cascade: ['persist', 'remove'])]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpire(): ?int
    {
        return $this->expire;
    }

    public function setExpire(int $expire): self
    {
        $this->expire = $expire;

        return $this;
    }

    public function getUser(): ?TrtUser
    {
        return $this->user;
    }

    public function setUser(?TrtUser $user): self
    {
        $this->user = $user;

        return $this;
    }
}
