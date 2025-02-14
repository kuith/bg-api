<?php

use Symfony\Component\Dotenv\Dotenv;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Tools\SchemaTool;

require dirname(__DIR__).'/vendor/autoload.php';

// Cargar variables de entorno de prueba
if (file_exists(dirname(__DIR__).'/.env.test')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test');
}

// Configuración del kernel
$kernel = new \App\Kernel('test', true);
$kernel->boot();

// Obtener el EntityManager del contenedor
/** @var EntityManagerInterface $entityManager */
$entityManager = $kernel->getContainer()->get(EntityManagerInterface::class);

// Obtenemos las metadatas de las entidades
$metadata = $entityManager->getMetadataFactory()->getAllMetadata();

// Creamos el esquema si no existe (para un entorno limpio de pruebas)
$schemaTool = new SchemaTool($entityManager);
$schemaTool->dropSchema($metadata); // Elimina tablas existentes
$schemaTool->createSchema($metadata); // Crea tablas necesarias

// En este punto tienes la base de datos limpia y configurada en memoria
