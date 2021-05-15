<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoitureRepository::class)
 */
class Voiture
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
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Climatiseur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Tabac;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="voiture", cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getClimatiseur(): ?string
    {
        return $this->Climatiseur;
    }

    public function setClimatiseur(string $Climatiseur): self
    {
        $this->Climatiseur = $Climatiseur;

        return $this;
    }

    public function getTabac(): ?string
    {
        return $this->Tabac;
    }

    public function setTabac(string $Tabac): self
    {
        $this->Tabac = $Tabac;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
