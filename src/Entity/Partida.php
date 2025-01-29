<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Partida
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $fecha;

    #[ORM\ManyToOne(targetEntity: Juego::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Juego $juego;

    /* #[ORM\ManyToOne(targetEntity: Jugador::class, inversedBy:'partidasGanadas')]
    #[ORM\JoinColumn(nullable: false)]
    private Jugador $ganador; */

    #[ORM\ManyToMany(targetEntity: Jugador::class, inversedBy:'partidasGanadas')]
    //#[ORM\JoinColumn(nullable: false)]
    private Collection $ganadores;

    #[ORM\ManyToMany(targetEntity: Jugador::class, inversedBy: 'partidas')]
    #[ORM\JoinTable(name: 'partidas_jugadores')]
    private Collection $jugadores;

    public function __construct()
    {
        $this->jugadores = new ArrayCollection();
        $this->ganadores = new ArrayCollection();
    }

    // Getters y Setters para cada propiedad

    public function getId(): int
    {
        return $this->id;
    }

    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    public function setFecha(\DateTime|string $fecha): self
    {
        if (is_string($fecha)) {
            $fecha = new \DateTime($fecha);
        }
        $this->fecha = $fecha;

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

/*     public function getGanador(): Jugador
    {
        return $this->ganador;
    }

    public function setGanador(Jugador $ganador): self
    {
        $this->ganador = $ganador;

        return $this;
    } */



    /* public function addJugadores(Autor $jugador): self
    {
        if (!$this->jugadores->contains($jugador)) {
            $this->jugadores->add($jugador);
        }

        return $this;
    } */

    public function setGanadores(Collection $ganadores): self
    {
        $this->jugadores = $ganadores;
        return $this;
    }

    public function getGanadores(): Collection
    {
        return $this->ganadores;
    }

    public function setJugadores(Collection $jugadores): self
    {
        $this->jugadores = $jugadores;

        return $this;
    }

    public function getJugadores(): Collection
    {
        return $this->jugadores;
    }
}
