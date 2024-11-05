<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'expansiones')]
class Expansion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nombre;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descripcion;

    #[ORM\ManyToOne(targetEntity: Juego::class, inversedBy: 'expansiones')]
    #[ORM\JoinColumn(name: 'juego_id', referencedColumnName: 'id', nullable: false)]
    private Juego $juego;

    #[ORM\Column(type: 'date')]
    private \DateTime $fechaLanzamiento;

    #[ORM\ManyToMany(targetEntity: Autor::class, inversedBy: 'expansiones')]
    #[ORM\JoinTable(name: 'expansiones_autores')]
    private Collection $autores;

    public function __construct()
    {
        $this->autores = new ArrayCollection();
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getJuego(): Juego
    {
        return $this->juego;
    }

    public function setJuego(Juego $juego): self
    {
        $this->juego = $juego;

        return $this;
    }

    public function getFechaLanzamiento(): \DateTime
    {
        return $this->fechaLanzamiento;
    }

    public function setFechaLanzamiento(\DateTime $fechaLanzamiento): self
    {
        $this->fechaLanzamiento = $fechaLanzamiento;

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
