<?php

namespace App\Entity;

use App\Repository\InfosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfosRepository::class)]
class Infos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $messageInfo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageInfo(): ?string
    {
        return $this->messageInfo;
    }

    public function setMessageInfo(string $messageInfo): static
    {
        $this->messageInfo = $messageInfo;

        return $this;
    }
}
