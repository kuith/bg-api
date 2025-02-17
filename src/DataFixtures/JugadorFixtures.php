<?php

namespace App\DataFixtures;

use App\Entity\Jugador;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JugadorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jugador1 = new Jugador();
        $jugador1->setNombre('Rafa');
        $jugador1->setCorreo('rafa.com');
        $jugador1->setRol('admin');
        $jugador1->setFechaRegistro(new \DateTime('2024-07-26')); // Fecha actual
        $jugador1->setPassword('1234'); // Contraseña sin hashear (¡NO RECOMENDADO PARA PRODUCCIÓN!)

        $manager->persist($jugador1);
        $this->addReference('jugador1', $jugador1);

        $jugador2 = new Jugador();
        $jugador2->setNombre('Paula');
        $jugador2->setCorreo('paula@example.com');
        $jugador2->setRol('admin');
        $jugador2->setFechaRegistro(new \DateTime()); // Fecha actual
        $jugador2->setPassword('5678'); // Contraseña sin hashear (¡NO RECOMENDADO PARA PRODUCCIÓN!)

        $manager->persist($jugador2);
        $this->addReference('jugador2', $jugador2);

        $jugador3 = new Jugador();
        $jugador3->setNombre('Miguel');
        $jugador3->setCorreo('miguel@example.com');
        $jugador3->setRol('jugador');
        $jugador3->setFechaRegistro(new \DateTime()); // Fecha actual
        $jugador3->setPassword('5678'); // Contraseña sin hashear (¡NO RECOMENDADO PARA PRODUCCIÓN!)

        $manager->persist($jugador3);
        $this->addReference('jugador3', $jugador3);

        $jugador4 = new Jugador();
        $jugador4->setNombre('Irene');
        $jugador4->setCorreo('irene@example.com');
        $jugador4->setRol('jugador');
        $jugador4->setFechaRegistro(new \DateTime('2024-07-26')); // Fecha actual
        $jugador4->setPassword('5678'); // Contraseña sin hashear (¡NO RECOMENDADO PARA PRODUCCIÓN!)

        $manager->persist($jugador4);
        $this->addReference('jugador4', $jugador4);

        for ($i = 5; $i <= 10; $i++) {
            $jugador = new Jugador();
            $jugador->setNombre('Jugador ' . $i);
            $jugador->setCorreo('jugador' . $i . '@example.com');
            $jugador->setRol('jugador');
            $jugador->setFechaRegistro(new \DateTime('now'));
            $jugador->setPassword('password'); 
            $manager->persist($jugador);
        }

        $manager->flush();
     }    
}

/* 
class JugadorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jugador = new Jugador();
        $jugador->setNombre('Jugador de prueba');
        $jugador->setCorreo('jugador@prueba.com');
        $jugador->setRol('jugador');
        $jugador->setFechaRegistro(new \DateTime('now'));
        $jugador->setPassword('password'); 
            

        $manager->persist($jugador);
        $manager->flush();
    }
} */