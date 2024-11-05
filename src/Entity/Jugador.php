<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Jugador
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $nombre;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $correo;

    #[ORM\Column(type: 'string', length: 10)]
    private string $rol; // Puede ser "jugador" o "admin"

    #[ORM\Column(type: 'date')]
    private \DateTime $fechaRegistro;

    #[ORM\OneToMany(mappedBy: 'ganador', targetEntity: Partida::class)]
    private Collection $partidasGanadas;

    #[ORM\ManyToMany(targetEntity: Partida::class, mappedBy: 'jugadores')]
    private Collection $partidas;

    public function __construct()
    {
        $this->partidasGanadas = new ArrayCollection();
        $this->partidas = new ArrayCollection();
    }

    public function setRol(string $rol): void
    {
        if (!in_array($rol, ['jugador', 'admin'])) {
            throw new \InvalidArgumentException('El rol debe ser "jugador" o "admin".');
        }
        $this->rol = $rol;
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

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function getFechaRegistro(): \DateTime
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(\DateTime $fechaRegistro): self
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    public function getPartidasGanadas(): Collection
    {
        return $this->partidasGanadas;
    }

    public function setPartidasGanadas(Collection $partidasGanadas): self
    {
        $this->partidasGanadas = $partidasGanadas;

        return $this;
    }

    public function getPartidas(): Collection
    {
        return $this->partidas;
    }

    public function setPartidas(Collection $partidas): self
    {
        $this->partidas = $partidas;

        return $this;
    }
}