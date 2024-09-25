<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExpansionRepository;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ExpansionRepository::class)]

class Expansion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['main'])]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'expansion')]
    private ?Juego $juego = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getJuego(): ?Juego
    {
        return $this->juego;
    }

    public function setJuego(?Juego $juego): static
    {
        $this->juego = $juego;

        return $this;
    }
}
