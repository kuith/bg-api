<?php
namespace App\Tests\Controller;
use App\Repository\JugadorRepository;

class JugadorControllerTest extends BaseWebTestCase
{
    protected $client;
    public function testObtenerListaJugadores()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $router = $client->getContainer()->get('router');
        $url = $router->generate('player_list', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_PATH);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar que no hubo excepciones
        $this->assertFalse($client->getResponse()->isServerError(), 'El servidor devolvió un error 500');

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

