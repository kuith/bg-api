<?php

namespace App\Tests\Controller;

use App\Entity\Jugador;
use App\Repository\JugadorRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JugadorControllerTest extends WebTestCase
{
    public function testObtenerListaJugadores()
    {
        $client = static::createClient();

        // **Opción 1: Usando el nombre de la ruta (RECOMENDADO)**
        $router = $client->getContainer()->get('router');
        $url = $router->generate('player_list', [], UrlGeneratorInterface::ABSOLUTE_PATH);
        var_dump($url);

        // **Opción 2: Usando la URL directamente**
        //$url = '/api/players/'; // Descomenta esta línea si quieres usar la URL directamente


        $client->request('GET', $url);
        

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        foreach ($data as $jugador) {
            $this->assertArrayHasKey('id', $jugador);
            $this->assertArrayHasKey('nombre', $jugador);
            $this->assertArrayHasKey('correo', $jugador); // Corregido a 'mail'
            $this->assertArrayHasKey('rol', $jugador); // Corregido a 'rol'
            // ... otras propiedades
        }

        $jugadorRepository = $this->getRepository(JugadorRepository::class);
        $numeroJugadoresEnBD = count($jugadorRepository->findAll());
        $this->assertCount($numeroJugadoresEnBD, $data);
    }

    private function getRepository(string $className)
    {
        return $this->getContainer()->get('doctrine')->getRepository($className);
    }
}