<?php

namespace App\DataFixtures;

use App\Entity\Juego;
use App\Entity\Jugador;
use App\Entity\Partida;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;

class PartidaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void

    {
        // Obtener las referencias de jugadores creadas en la fixture de Jugador
        $jugador1 = $this->getReference('jugador1', Jugador::class);
        $jugador2 = $this->getReference('jugador2', Jugador::class);
        $jugador3 = $this->getReference('jugador3', Jugador::class);
        $jugador4 = $this->getReference('jugador4', Jugador::class);
        $juego1 = $this->getReference('juego1', Juego::class);
        $juego2 = $this->getReference('juego2', Juego::class);
        $juego3 = $this->getReference('juego3', Juego::class);

        $jugadores = new ArrayCollection([$jugador1, $jugador2, $jugador3, $jugador4]);
        $jugadores1 = new ArrayCollection([$jugador1, $jugador2]);
        $jugadores2 = new ArrayCollection([$jugador3, $jugador4]);

        $ganador1 = new ArrayCollection([$jugador1]);
        $ganador2 = new ArrayCollection([$jugador2]);
        $ganador3 = new ArrayCollection([$jugador3]);
        $ganador4 = new ArrayCollection([$jugador4]);

//////////////////////////////////////////////
        $partida1 = new Partida();
        $partida1->setFecha(new \DateTime('2021-01-01 00:00:00'));
        $partida1->setJugadores($jugadores);
        $partida1->setGanadores($ganador2);
        $partida1->setJuego( $juego1);
        
        $manager->persist($partida1);
//////////////////////////////////////////////
        $partida2 = new Partida();
        $partida2->setFecha(new \DateTime('2021-01-01 00:00:00'));
        $partida2->setJugadores($jugadores1);
        $partida2->setGanadores($ganador2);
        $partida2->setJuego( $juego2);
        
        $manager->persist($partida2);
//////////////////////////////////////////////
        $partida3 = new Partida();
        $partida3->setFecha(new \DateTime('2021-01-01 00:00:00'));
        $partida3->setJugadores($jugadores1);
        $partida3->setGanadores($ganador1);
        $partida3->setJuego( $juego2);

        $manager->persist($partida3);
//////////////////////////////////////////////
        $partida4 = new Partida();
        $partida4->setFecha(new \DateTime('2021-01-01 00:00:00'));
        $partida4->setJugadores($jugadores2);
        $partida4->setGanadores($ganador3);
        $partida4->setJuego( $juego3);

        $manager->persist($partida4);
//////////////////////////////////////////////
        $partida5 = new Partida();
        $partida5->setFecha(new \DateTime('2021-01-01 00:00:00'));
        $partida5->setJugadores($jugadores);
        $partida5->setGanadores($ganador2);
        $partida5->setJuego( $juego3);

        $manager->persist($partida5);
//////////////////////////////////////////////
        
        $manager->flush();
     }    
}