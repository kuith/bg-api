<?php
namespace App\Tests\Controller;

use App\Repository\JugadorRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JugadorControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        // Asegurarse de que Doctrine haya inicializado la base de datos correctamente
        $this->initializeDatabase();
    }

    // Este método asegura que la base de datos en memoria está lista antes de ejecutar las pruebas
    private function initializeDatabase(): void
    {
        // Obtener el EntityManager desde el contenedor
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        // Inicializar las tablas en memoria para las pruebas
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
        
        // Crear las tablas según las entidades registradas
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($classes);

        // Opcionalmente, si quieres hacer una limpieza antes de cada test
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($entityManager);
        $purger->purge();
    }


    public function testObtenerListaJugadores()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $router = $client->getContainer()->get('router');
        $url = $router->generate('player_list', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_PATH);

        // Hacer la solicitud GET
        $client->request('GET', $url);

        // Verificar que la respuesta sea 200 OK
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        // Validar que los jugadores devueltos tienen las claves correctas
        foreach ($data as $jugador) {
            $this->assertArrayHasKey('id', $jugador);
            $this->assertArrayHasKey('nombre', $jugador);
            $this->assertArrayHasKey('correo', $jugador);
            $this->assertArrayHasKey('rol', $jugador);
        }

        // Verificar que el número de jugadores en la respuesta coincida con el número en la base de datos
        $jugadorRepository = $client->getContainer()->get(JugadorRepository::class);
        $numeroJugadoresEnBD = count($jugadorRepository->findAll());
        $this->assertCount($numeroJugadoresEnBD, $data);
    }
}

/* 
namespace App\DataFixtures;

use App\Entity\Jugador;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class JugadorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $jugador = new Jugador();
        $jugador->setNombre('Jugador de prueba');
        $jugador->setCorreo('jugador@prueba.com');
        $jugador->setRol('jugador');
        
        $manager->persist($jugador);
        $manager->flush();
    }
} */
