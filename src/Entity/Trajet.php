<?php

namespace App\Entity;

use App\Repository\TrajetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TrajetRepository::class)
 */
class Trajet
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
    private $Depart;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Arrivee;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Range(
     *     min="now"
     * )
     */
    private $Date;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $places;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trajets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepart(): ?string
    {
        return $this->Depart;
    }

    public function setDepart(string $depart): self
    {
        $this->Depart = $depart;

        return $this;
    }

    public function getArrivee(): ?string
    {
        return $this->Arrivee;
    }

    public function setArrivee(string $arrivee): self
    {
        $this->Arrivee = $arrivee;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->Date = $date;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(int $places): self
    {
        $this->places = $places;

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
