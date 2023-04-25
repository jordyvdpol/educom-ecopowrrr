<?php

namespace App\Entity;

use App\Repository\KlantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KlantRepository::class)]
class Klant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 7)]
    private ?string $postcode = null;

    #[ORM\Column(length: 5)]
    private ?string $huisnummer = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getHuisnummer(): ?string
    {
        return $this->huisnummer;
    }

    public function setHuisnummer(string $huisnummer): self
    {
        $this->huisnummer = $huisnummer;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
