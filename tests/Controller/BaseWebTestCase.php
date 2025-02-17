<?php
namespace App\Tests\Controller;

use App\DataFixtures\JugadorFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

abstract class BaseWebTestCase extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        set_exception_handler(null);
        $this->client->catchExceptions(false);

        $this->initializeDatabase();
        $this->loadFixtures();
    }

    protected function initializeDatabase(): void
    {
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $schemaTool = new SchemaTool($entityManager);
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($classes);

        $purger = new ORMPurger($entityManager);
        $purger->purge();
    }

    protected function loadFixtures(): void
    {
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader();
        $loader->addFixture(new JugadorFixtures());

        $purger = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
}
