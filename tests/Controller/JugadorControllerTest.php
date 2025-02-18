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
        $url = $this->getUrl('player_list');

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los jugadores
        $this->validateJugadorStructure($data);

        // Comparar el número de jugadores
        $this->assertCountJugadores($client, $data);
    }

    public function testObtenerJugadorPorId()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('player_show', ['id' => 1]); // Usamos un ID que sabemos que existe en la base de datos


        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        //echo "Generated URL: " . $url . "\n";  // Aquí se imprimirá la URL generada

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el jugador recibido tiene el ID correcto
        $this->assertEquals(1, $data['id']); // Verificamos que el ID sea el que pedimos

    }

    ///Métodos auxiliares////

    //Verificar respuesta
    private function VerifyResponse($client){
        $this->assertResponse($client, 200);
    }

    //verificar estructura de respuesta
    private function verifyReponseExtrucre($client, $data){
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }

    //GetUrl
    private function getUrl(string $routeName, array $parameters = []): string
    {
        $router = $this->client->getContainer()->get('router');
        return $router->generate($routeName, $parameters, \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    //Validar respuesta
    private function assertResponse($client, int $expectStatusCode): void{
        // Verificar que no hubo excepciones
        $this->assertFalse($client->getResponse()->isServerError(), 'El servidor devolvió un error 500');

        // Verificar que la respuesta sea 200 OK
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    //Validar estructura de un jugador
    private function validateJugadorStructure(array $data): void
    {
        foreach ($data as $jugador) {
            $this->assertArrayHasKey('id', $jugador);
            $this->assertArrayHasKey('nombre', $jugador);
            $this->assertArrayHasKey('correo', $jugador);
            $this->assertArrayHasKey('rol', $jugador);
        }
    }

    // Método para verificar que el número de jugadores coincide con la base de datos
    private function assertCountJugadores($client, array $data): void
    {
        $jugadorRepository = $client->getContainer()->get(JugadorRepository::class);
        $numeroJugadoresEnBD = count($jugadorRepository->findAll());
        $this->assertCount($numeroJugadoresEnBD, $data);
    }
}

