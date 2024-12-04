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

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $editorialMadre = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $editorialLocal = null;

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