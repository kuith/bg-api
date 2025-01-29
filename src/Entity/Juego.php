<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'juego')]

class Juego
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $nombre;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $baseExpansion; // Puede ser "base" o "expansion"

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Juego $juegoBase = null;

    #[ORM\Column(type: 'text')]
    private string $tipo;

    #[ORM\Column(type: 'text')]
    private string $descripcion;

    #[ORM\Column(type: 'integer')]
    private int $minJugadores;

    #[ORM\Column(type: 'integer')]
    private int $maxJugadores;

    #[ORM\Column(type: 'float')]
    private float $precio;

    #[ORM\Column(type: 'boolean')]
    private bool $dispAutoma;

    #[ORM\ManyToMany(targetEntity: Autor::class, inversedBy: 'juegos')]
    //#[ORM\JoinTable(name: 'juegos_autores')]
    private Collection $autores;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $editorialMadre = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $editorialLocal = null;

    public function __construct()
    {
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

    public function setBaseExpansion(string $baseExpansion): void
    {
        if (!in_array($baseExpansion, ['base', 'expansion'])) {
            throw new \InvalidArgumentException('El tipo debe ser "base" o "expansion".');
        }
        $this->baseExpansion = $baseExpansion;
    }
    
    public function getBaseExpansion(): string
    {
        return $this->baseExpansion;
    }

    public function setJuegoBase(?Juego $juegoBase): self
    {
        $this->juegoBase = $juegoBase;
        return $this;
    }

    public function getJuegoBase(): ?Juego
    {
        return $this->juegoBase;
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

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function getMinJugadores(): int
    {
        return $this->minJugadores;
    }

    public function setMinJugadores(int $minJugadores): self
    {
        $this->minJugadores = $minJugadores;

        return $this;
    }

    public function getMaxJugadores(): int
    {
        return $this->maxJugadores;
    }

    public function setMaxJugadores(int $maxJugadores): self
    {
        $this->maxJugadores = $maxJugadores;

        return $this;
    }

    public function getPrecio(): string
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

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

    public function getAutores(): Collection
    {
        return $this->autores;
    }

    public function addAutor(Autor $autor): self
    {
        if (!$this->autores->contains($autor)) {
            $this->autores->add($autor);
        }

        return $this;
    }

    public function removeAutor(Autor $autor): self
    {
        $this->autores->removeElement($autor);

        return $this;
    }

    public function getEditorialMadre(): ?string
    {
        return $this->editorialMadre;
    }

    public function setEditorialMadre(string $editorialMadre): self
    {
        $this->editorialMadre = $editorialMadre;
        return $this;
    }

    public function getEditorialLocal(): ?string
    {
        return $this->editorialLocal;
    }

    public function setEditorialLocal(string $editorialLocal): self
    {
        $this->editorialLocal = $editorialLocal;
        return $this;
    }
}