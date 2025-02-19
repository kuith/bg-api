<?php

namespace App\DataFixtures;

use App\Entity\Autor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AutorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $autor1 = new Autor();
        $autor1->setNombre('Uwe Rosenberg');
        $autor1->setNacionalidad('Alemania');
        
        $manager->persist($autor1);
        $this->addReference('autor1', $autor1);

        $autor2 = new Autor();
        $autor2->setNombre('Stefan Feld');
        $autor2->setNacionalidad('Alemania');
        
        $manager->persist($autor2);
        $this->addReference('autor2', $autor2);

        $autor3 = new Autor();
        $autor3->setNombre('Richard Garfield');
        $autor3->setNacionalidad('Estados Unidos');
        
        $manager->persist($autor3);
        $this->addReference('autor3', $autor3);

        $autor4 = new Autor();
        $autor4->setNombre('Eloy Pujadas');
        $autor4->setNacionalidad('España');
        
        $manager->persist($autor4);
        $this->addReference('autor4', $autor4);

        $autor5 = new Autor();
        $autor5->setNombre('Alberto Corral');
        $autor5->setNacionalidad('España');
        
        $manager->persist($autor5);
        $this->addReference('autor5', $autor5);


        $manager->flush();
     }    
}