<?php

namespace App\Entity;

use App\Repository\InfoSecuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfoSecuRepository::class)]
class InfoSecu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $MessageSecu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageSecu(): ?string
    {
        return $this->MessageSecu;
    }

    public function setMessageSecu(string $MessageSecu): static
    {
        $this->MessageSecu = $MessageSecu;

        return $this;
    }
}
