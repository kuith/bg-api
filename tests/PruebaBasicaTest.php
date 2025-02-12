<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PruebaBasicaTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager'); // Or better: $kernel->getContainer()->get(EntityManagerInterface::class);

        // Or, even better (if possible for your setup):
        //$this->entityManager = $this->getEntityManager(); // If you have a helper method for this.

        // ... any other setup ...
    }

    public function testSomething()
    {
        $this->assertInstanceOf(EntityManagerInterface::class, $this->entityManager);
        // ... your test logic using $this->entityManager ...
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Close the entity manager
        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null; // Important: set to null to avoid memory leaks
        }
    }
}