<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\JuegoRepository;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: JuegoRepository::class)]
#[
    ORM\UniqueConstraint (
        name:'Juego',
        columns:['nombre']
    )
]
class Juego
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['main'])]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main'])]
    private ?string $urimagen = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main'])]
    private ?string $dimensiones_caja = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['main'])]
    private ?int $precio = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main'])]
    private ?string $rango_jugadores = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main'])]
    private ?string $autores = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main'])]
    private ?string $editorial_madre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main'])]
    private ?string $editorial_local = null;

    /**
     * @var Collection<int, Expansion>
     */
    #[ORM\OneToMany(targetEntity: Expansion::class, mappedBy: 'juego')]
    private Collection $expansions;

    public function __construct()
    {
        $this->expansions = new ArrayCollection();
    }

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

    public function getUrimagen(): ?string
    {
        return $this->urimagen;
    }

    public function setUrimagen(?string $urimagen): static
    {
        $this->urimagen = $urimagen;

        return $this;
    }

    public function getDimensionesCaja(): ?string
    {
        return $this->dimensiones_caja;
    }

    public function setDimensionesCaja(?string $dimensiones_caja): static
    {
        $this->dimensiones_caja = $dimensiones_caja;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(?int $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getRangoJugadores(): ?string
    {
        return $this->rango_jugadores;
    }

    public function setRangoJugadores(string $rango_jugadores): static
    {
        $this->rango_jugadores = $rango_jugadores;

        return $this;
    }

    public function getAutores(): ?string
    {
        return $this->autores;
    }

    public function setAutores(?string $autores): static
    {
        $this->autores = $autores;

        return $this;
    }

    public function getEditorialMadre(): ?string
    {
        return $this->editorial_madre;
    }

    public function setEditorialMadre(?string $editorial_madre): static
    {
        $this->editorial_madre = $editorial_madre;

        return $this;
    }

    public function getEditorialLocal(): ?string
    {
        return $this->editorial_local;
    }

    public function setEditorialLocal(?string $editorial_local): static
    {
        $this->editorial_local = $editorial_local;

        return $this;
    }

    /**
     * @return Collection<int, Expansion>
     */
    public function getExpansions(): Collection
    {
        return $this->expansions;
    }

    public function addExpansion(Expansion $expansion): static
    {
        if (!$this->expansions->contains($expansion)) {
            $this->expansions->add($expansion);
            $expansion->setJuego($this);
        }

        return $this;
    }

    public function removeExpansion(Expansion $expansion): static
    {
        if ($this->expansions->removeElement($expansion)) {
            // set the owning side to null (unless already changed)
            if ($expansion->getJuego() === $this) {
                $expansion->setJuego(null);
            }
        }

        return $this;
    }
}
