<?php

namespace App\Entity;

use App\Repository\DummyDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DummyDataRepository::class)]
class DummyData
{

    // #[ORM\ManyToOne(targetEntity: RelatedData::class)]
    // #[ORM\JoinColumn(name: "related_data_id", referencedColumnName: "id")]
    // private $relatedData;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $message_id = null;

    #[ORM\ManyToOne(inversedBy: 'test')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Klanten $klantnummer = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column(length: 100)]
    private ?string $date = null;

    #[ORM\Column]
    private ?int $jaar = null;

    #[ORM\Column]
    private ?int $maand = null;

    #[ORM\Column(nullable: true)]
    private ?float $total_yield = null;

    #[ORM\Column(nullable: true)]
    private ?float $month_yield = null;

    #[ORM\Column(nullable: true)]
    private ?float $total_surplus = null;

    #[ORM\Column(nullable: true)]
    private ?float $month_surplus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageId(): ?string
    {
        return $this->message_id;
    }

    public function setMessageId(string $message_id): self
    {
        $this->message_id = $message_id;

        return $this;
    }

    public function getKlantnummer(): ?Klanten
    {
        return $this->klantnummer;
    }

    public function setKlantnummer(?Klanten $klantnummer): self
    {
        $this->klantnummer = $klantnummer;

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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getJaar(): ?int
    {
        return $this->jaar;
    }

    public function setJaar(int $jaar): self
    {
        $this->jaar = $jaar;

        return $this;
    }

    public function getMaand(): ?int
    {
        return $this->maand;
    }

    public function setMaand(int $maand): self
    {
        $this->maand = $maand;

        return $this;
    }

    public function getTotalYield(): ?float
    {
        return $this->total_yield;
    }

    public function setTotalYield(?float $total_yield): self
    {
        $this->total_yield = $total_yield;

        return $this;
    }

    public function getMonthYield(): ?float
    {
        return $this->month_yield;
    }

    public function setMonthYield(?float $month_yield): self
    {
        $this->month_yield = $month_yield;

        return $this;
    }

    public function getTotalSurplus(): ?float
    {
        return $this->total_surplus;
    }

    public function setTotalSurplus(?float $total_surplus): self
    {
        $this->total_surplus = $total_surplus;

        return $this;
    }

    public function getMonthSurplus(): ?float
    {
        return $this->month_surplus;
    }

    public function setMonthSurplus(?float $month_surplus): self
    {
        $this->month_surplus = $month_surplus;

        return $this;
    }
}
