<?php

namespace App\Entity;

use App\Repository\RencontresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RencontresRepository::class)]
class Rencontres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Artiste = null;

    #[ORM\Column(length: 255)]
    private ?string $Scene = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure = null;

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
        return $this->Scene;
    }

    public function setScene(string $Scene): static
    {
        $this->Scene = $Scene;

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

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
    }
}
