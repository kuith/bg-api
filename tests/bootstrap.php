<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/.env.test')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test');
}

$kernel = new \App\Kernel('test', true);
$kernel->boot();

/** @var EntityManagerInterface $entityManager */
/**$entityManager = $kernel->getContainer()->get(EntityManagerInterface::class);*/
$metadata = $entityManager->getMetadataFactory()->getAllMetadata();

//$schemaTool = new SchemaTool($entityManager);
//$schemaTool->dropSchema($metadata);
//$schemaTool->createSchema($metadata);


