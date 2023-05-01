<?php

namespace App\Entity;

use App\Repository\PrijsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrijsRepository::class)]
class Prijs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4)]
    private ?string $jaar = null;

    #[ORM\Column(length: 2)]
    private ?string $maand = null;

    #[ORM\Column]
    private ?float $inkoop_prijs_KwH = null;

    #[ORM\Column]
    private ?float $verkoop_prijs_KwH = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJaar(): ?string
    {
        return $this->jaar;
    }

    public function setJaar(string $jaar): self
    {
        $this->jaar = $jaar;

        return $this;
    }

    public function getMaand(): ?string
    {
        return $this->maand;
    }

    public function setMaand(string $maand): self
    {
        $this->maand = $maand;

        return $this;
    }

    public function getInkoopPrijsKwH(): ?float
    {
        return $this->inkoop_prijs_KwH;
    }

    public function setInkoopPrijsKwH(float $inkoop_prijs_KwH): self
    {
        $this->inkoop_prijs_KwH = $inkoop_prijs_KwH;

        return $this;
    }

    public function getVerkoopPrijsKwH(): ?float
    {
        return $this->verkoop_prijs_KwH;
    }

    public function setVerkoopPrijsKwH(float $verkoop_prijs_KwH): self
    {
        $this->verkoop_prijs_KwH = $verkoop_prijs_KwH;

        return $this;
    }
}
