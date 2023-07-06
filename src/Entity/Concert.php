<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConcertRepository::class)]
class Concert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Artiste = null;

    #[ORM\Column(length: 255)]
    private ?string $scene = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $Horaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtiste(): ?string
    {
        return $this->Artiste;
    }

    public function setArtiste(string $Artiste): static
    {
        $this->Artiste = $Artiste;

        return $this;
    }

    public function getScene(): ?string
    {
        return $this->scene;
    }

    public function setScene(string $scene): static
    {
        $this->scene = $scene;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getHoraire(): ?\DateTimeInterface
    {
        return $this->Horaire;
    }

    public function setHoraire(\DateTimeInterface $Horaire): static
    {
        $this->Horaire = $Horaire;

        return $this;
    }
}
