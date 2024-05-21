<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArtisteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArtisteRepository::class)]
#[ORM\Table(name: "artiste")]
#[ApiResource]
class Artiste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['concert:read'])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['concert:read'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: "scene", targetEntity: Concert::class)]
    #[Groups(['concert:read'])]
    private Collection $concerts;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getConcerts(): Collection
    {
        return $this->concerts;
    }

    public function addConcert(Concert $concert): self
    {
        if (!$this->concerts->contains($concert)) {
            $this->concerts[] = $concert;
            $concert->setArtiste($this);
        }

        return $this;
    }
}
