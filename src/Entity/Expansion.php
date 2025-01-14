<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Expansion extends Juego
{
    #[ORM\ManyToOne(targetEntity: Juego::class)]
    #[ORM\JoinColumn(name: 'juego_base_id', referencedColumnName: 'id', nullable: true)]
    private Juego $juegoBase;

    public function getJuegoBase(): Juego
    {
        return $this->juegoBase;
    }

    public function setJuegoBase(Juego $juegoBase): self
    {
        $this->juegoBase = $juegoBase;
        return $this;
    }
}