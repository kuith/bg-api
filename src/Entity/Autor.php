<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields: ['nombre'], message: 'El nombre de autor debe ser único.')]
#[ORM\Entity]
class Autor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nombre;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $nacionalidad = null;

    #[ORM\ManyToMany(targetEntity: Juego::class, mappedBy: 'autores')]
    private Collection $juegos;

    public function __construct()
    {
        $this->juegos = new ArrayCollection();
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

    public function getNacionalidad(): ?string
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(?string $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;
        return $this;
    }

    public function getJuegos(): Collection
    {
        return $this->juegos;
    }

    public function addJuego(Juego $juego): self
    {
        if (!$this->juegos->contains($juego)) {
            $this->juegos->add($juego);
        }

        return $this;
    }

    public function removeJuego(Juego $juego): self
    {
        $this->juegos->removeElement($juego);

        return $this;
    }
}