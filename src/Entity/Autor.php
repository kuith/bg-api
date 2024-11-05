<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Autor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nombre;

    #[ORM\ManyToMany(targetEntity: Juego::class, mappedBy: 'autores')]
    //#[ORM\JoinTable(name: 'juegos_autores')]
    private Collection $juegos;

    #[ORM\ManyToMany(targetEntity: Expansion::class, mappedBy: 'autores')]
    private Collection $expansiones;

    public function __construct()
    {
        $this->juegos = new ArrayCollection();
        $this->expansiones = new ArrayCollection();
    }

    // Getters y Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getJuegos(): Collection
    {
        return $this->juegos;
    }

    public function setJuegos(Collection $juegos): self
    {
        $this->juegos = $juegos;

        return $this;
    }

    public function getExpansiones(): Collection
    {
        return $this->expansiones;
    }

    public function setExpansiones(Collection $expansiones): self
    {
        $this->expansiones = $expansiones;

        return $this;
    }
}