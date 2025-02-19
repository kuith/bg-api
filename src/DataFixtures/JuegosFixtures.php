<?php

namespace App\DataFixtures;

use App\Entity\Juego;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JuegoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $juego1 = new Juego();
        $juego1->setNombre('Terraforming Mars');
 
        
        $manager->persist($juego1);
        $this->addReference('juego1', $juego1);

        $manager->flush();
     }    
}