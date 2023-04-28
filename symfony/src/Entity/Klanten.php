<?php

namespace App\Entity;

use App\Repository\KlantenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KlantenRepository::class)]
class Klanten
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12)]
    private ?string $klantnummer = null;

    #[ORM\Column(length: 20)]
    private ?string $voornaam = null;

    #[ORM\Column(length: 100)]
    private ?string $achternaam = null;

    #[ORM\Column(length: 7)]
    private ?string $postcode = null;

    #[ORM\Column(length: 10)]
    private ?string $huisnummer = null;

    #[ORM\Column(length: 30)]
    private ?string $stad = null;

    #[ORM\Column(length: 100)]
    private ?string $gemeente = null;

    #[ORM\Column(length: 100)]
    private ?string $provincie = null;

    #[ORM\OneToMany(mappedBy: 'klantnummer', targetEntity: DummyData::class)]
    private Collection $test;

    public function __construct()
    {
        $this->test = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKlantnummer(): ?string
    {
        return $this->klantnummer;
    }

    public function setKlantnummer(string $klantnummer): self
    {
        $this->klantnummer = $klantnummer;

        return $this;
    }

    public function getVoornaam(): ?string
    {
        return $this->voornaam;
    }

    public function setVoornaam(string $voornaam): self
    {
        $this->voornaam = $voornaam;

        return $this;
    }

    public function getAchternaam(): ?string
    {
        return $this->achternaam;
    }

    public function setAchternaam(string $achternaam): self
    {
        $this->achternaam = $achternaam;

        return $this;
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

    public function getStad(): ?string
    {
        return $this->stad;
    }

    public function setStad(string $stad): self
    {
        $this->stad = $stad;

        return $this;
    }

    public function getGemeente(): ?string
    {
        return $this->gemeente;
    }

    public function setGemeente(string $gemeente): self
    {
        $this->gemeente = $gemeente;

        return $this;
    }

    public function getProvincie(): ?string
    {
        return $this->provincie;
    }

    public function setProvincie(string $provincie): self
    {
        $this->provincie = $provincie;

        return $this;
    }

    /**
     * @return Collection<int, DummyData>
     */
    public function getTest(): Collection
    {
        return $this->test;
    }

    public function addTest(DummyData $test): self
    {
        if (!$this->test->contains($test)) {
            $this->test->add($test);
            $test->setKlantnummer($this);
        }

        return $this;
    }

    public function removeTest(DummyData $test): self
    {
        if ($this->test->removeElement($test)) {
            // set the owning side to null (unless already changed)
            if ($test->getKlantnummer() === $this) {
                $test->setKlantnummer(null);
            }
        }

        return $this;
    }
}
