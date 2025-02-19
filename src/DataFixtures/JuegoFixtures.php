<?php

namespace App\DataFixtures;

use App\Entity\Autor;
use App\Entity\Juego;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class JuegoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $juego1 = new Juego();
        $juego1->setNombre('Terraforming Mars');
        $juego1->setBaseExpansion('base');
        $juego1->setTipo('Estrategia');
        $juego1->setDescripcion('Juego de estrategia en el que los jugadores compiten por terraformar Marte');
        $juego1->setAnioPublicacion(2016);
        $juego1->setDispAutoma(false);
        $juego1->setEditorialLocal('Maldito Games');
        $juego1->setEditorialMadre('Stronghold Games');
        $juego1->setPrecio(50);
        $juego1->setMinJugadores(2);
        $juego1->setMaxJugadores(5);

        //Obtener los autores desde `AutorFixtures`
        $autor1 = $this->getReference('autor1',Autor::class); // Uwe Rosenberg
        $autor2 = $this->getReference('autor2', Autor::class); // Stefan Feld

        $juego1->addAutor($autor1);
        $juego1->addAutor($autor2);
        
        $manager->persist($juego1);
        $this->addReference('juego1', $juego1);
///////////////////////////////////////////////
        $juego2 = new Juego();
        $juego2->setNombre('Gaia Project');
        $juego2->setBaseExpansion('base');
        $juego2->setTipo('Estrategia');
        $juego2->setDescripcion('Juego de estrategia en el que los jugadores compiten por terraformar planetas');
        $juego2->setAnioPublicacion(2017);
        $juego2->setDispAutoma(false);
        $juego2->setEditorialLocal('Maldito Games');
        $juego2->setEditorialMadre('Stronghold Games');
        $juego2->setPrecio(50);
        $juego2->setMinJugadores(2);
        $juego2->setMaxJugadores(5);

        //Obtener los autores desde `AutorFixtures`
        $autor2 = $this->getReference('autor2', Autor::class); // Stefan Feld
        $juego2->addAutor($autor2);
        
        $manager->persist($juego2);
        $this->addReference('juego2', $juego2);

///////////////////////////////////////////////
        $juego3 = new Juego();
        $juego3->setNombre('La flota perdida');
        $juego3->setBaseExpansion('expansion');
        $juego2 = $this->getReference('juego2', Juego::class); //Gaia Project
        $juego3->setJuegoBase($juego2);
        $juego3->setTipo('Estrategia');
        $juego3->setDescripcion('Expansión para Gaia Project');
        $juego3->setAnioPublicacion(2018);
        $juego3->setDispAutoma(true);
        $juego3->setEditorialLocal('Maldito Games');
        $juego3->setEditorialMadre('Stronghold Games');
        $juego3->setPrecio(20);
        $juego3->setMinJugadores(1);
        $juego3->setMaxJugadores(5);
        //Obtener los autores desde `AutorFixtures`
        $autor1 = $this->getReference('autor1', Autor::class);
        $juego3->addAutor($autor1);
        
        $manager->persist($juego3);
        $this->addReference('juego3', $juego3);
        
///////////////////////////////////////////////
        $juego4 = new Juego();
        $juego4->setNombre('root');
        $juego4->setBaseExpansion('base');
        $juego4->setTipo('Lucha');
        $juego4->setDescripcion('Juego de lucha en el que los jugadores compiten por el control del bosque');
        $juego4->setAnioPublicacion(2018);
        $juego4->setDispAutoma(false);
        $juego4->setEditorialLocal('Maldito Games');
        $juego4->setEditorialMadre('Leder Games');
        $juego4->setPrecio(50);
        $juego4->setMinJugadores(2);
        $juego4->setMaxJugadores(4);
        //Obtener los autores desde `AutorFixtures`
        $autor3 = $this->getReference('autor3', Autor::class);
        $juego4->addAutor($autor3);

        $manager->persist($juego4);
        $this->addReference('juego4', $juego4);

        ///////////////////////////////////////////////
        $juego5 = new Juego();
        $juego5->setNombre('los cachivaches');
        $juego5->setBaseExpansion('expansion');
        $juego4 = $this->getReference('juego4', Juego::class); //Root
        $juego5->setJuegoBase($juego4);
        $juego5->setTipo('Lucha');
        $juego5->setDescripcion('Expansión para Root');
        $juego5->setAnioPublicacion(2019);
        $juego5->setDispAutoma(true);
        $juego5->setEditorialLocal('Maldito Games');
        $juego5->setEditorialMadre('Leder Games');
        $juego5->setPrecio(20);
        $juego5->setMinJugadores(1);
        $juego5->setMaxJugadores(4);
        //Obtener los autores desde `AutorFixtures`
        $autor3 = $this->getReference('autor3', Autor::class);
        $autor4 = $this->getReference('autor4', Autor::class);
        $juego5->addAutor($autor3);
        $juego5->addAutor($autor4);

        $manager->persist($juego5);
        $this->addReference('juego5', $juego5);


//////////////////////////////////////////////
        
        $manager->flush();
     }    
}