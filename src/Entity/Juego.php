<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Juego
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $nombre;

    #[ORM\Column(type: 'text')]
    private string $descripcion;

    #[ORM\Column(type: 'boolean')]
    private bool $dispAutoma;

    #[ORM\OneToMany(mappedBy: 'juego', targetEntity: Expansion::class)]
    private Collection $expansiones;

    #[ORM\ManyToMany(targetEntity: Autor::class, inversedBy: 'juegos')]
    //#[ORM\JoinTable(name: 'juegos_autores')]
    private Collection $autores;

    public function __construct()
    {
        $this->expansiones = new ArrayCollection();
        $this->autores = new ArrayCollection();
    }

    

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

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function isDispAutoma(): bool
    {
        return $this->dispAutoma;
    }

    public function setDispAutoma(bool $dispAutoma): self
    {
        $this->dispAutoma = $dispAutoma;

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

    public function getAutores(): Collection
    {
        return $this->autores;
    }

    public function setAutores(Collection $autores): self
    {
        $this->autores = $autores;

        return $this;
    }
}