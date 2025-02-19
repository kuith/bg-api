<?php
namespace App\Tests\Controller;
use App\Repository\AutorRepository;

class AutorControllerTest extends BaseWebTestCase
{
    protected $client;
    public function testObtenerListaAutores()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('author_list');

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los jugadores
        $this->validateAutorStructure($data);

        // Comparar el número de jugadores
        $this->assertCountAutores($client, $data);
    }

    public function testObtenerAutorPorId()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('author_show', ['id' => 1]); // Usamos un ID que sabemos que existe en la base de datos


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

    Public function testObtenerAutorPorNombre ()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('author_findByname', ['nombre' => 'Uwe Rosenberg']);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el autor recibido tiene el NOMBRE correcto
        $this->assertEquals('Uwe Rosenberg', $data['nombre']); 
    }

    Public function testObtenerAutorPorNacionalidad()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('author_findByNac', ['nacionalidad' => 'Alemania']);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el autor recibido tiene el ROL correcto
        foreach ($data as $autor) {
            $this->assertEquals("Alemania", $autor['nacionalidad'], "Se encontró ningún autor con un una nacionalidad distint a 'Alemania'");
        }
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
    private function validateAutorStructure(array $data): void
    {
        foreach ($data as $jugador) {
            $this->assertArrayHasKey('id', $jugador);
            $this->assertArrayHasKey('nombre', $jugador);
            $this->assertArrayHasKey('nacionalidad', $jugador);
        }
    }

    // Método para verificar que el número de autores coincide con la base de datos
    private function assertCountAutores($client, array $data): void
    {
        $autorRepository = $client->getContainer()->get(AutorRepository::class);
        $numeroAutoresEnBD = count($autorRepository->findAll());
        $this->assertCount($numeroAutoresEnBD, $data);
    }
}

