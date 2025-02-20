<?php
namespace App\Tests\Controller;
use App\Entity\Autor;
use App\Repository\JuegoRepository;

class JuegoControllerTest extends BaseWebTestCase
{
    protected $client;
    public function testObtenerListaJuegos()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_list');

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los juegos
        $this->validateAutorStructure($data);

        // Comparar el número de jugadores
        $this->assertCountJuegos($client, $data);
    }

    public function testObtenerJuegoPorId()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_show', ['id' => 1]); 


        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        //echo "Generated URL: " . $url . "\n";  // Aquí se imprimirá la URL generada

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el juego recibido tiene el ID correcto
        $this->assertEquals(1, $data['id']); // Verificamos que el ID sea el que pedimos

    }

    Public function testObtenerJuegoPorNombre ()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByName', ['nombre' => 'root']);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el juego recibido tiene el NOMBRE correcto
        $this->assertEquals('root', $data['nombre']); 
    }

    Public function testObtenerJuegoPorAnioPublicacion()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByYear', ['anio' => 2018]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el juego recibido tiene el año de publicacion correcto
        foreach ($data as $juego) {
            $this->assertEquals(2018, $juego['anioPublicacion'], "Se encontró un juego con un año de publicacion a 2018");
        }
    }

    Public function testObtenerJuegoPorEditorialLocal()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByLocalEditorial', ['editorialLocal' => 'Maldito Games']);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el juego recibido tiene la editorial local correcta
        foreach ($data as $juego) {
            $this->assertEquals('Maldito Games', $juego['editorialLocal'], "Se encontró un juego con un una editorial local distinta a 'Maldito Games'");
        }
    }

    Public function testObtenerJuegoPorEditorialMadre()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByOriginEditorial', ['editorialMadre' => 'Stronghold Games']);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el juego recibido tiene la editorial madre correcta
        foreach ($data as $juego) {
            $this->assertEquals('Stronghold Games', $juego['editorialMadre'], "Se encontró un juego con un una editorial local distinta a 'Stronghold Games'");
        }
    }
    
    Public function testObtenerJuegoPorRangoDePrecios()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByPriceRange', ['minPrice' => 20, 'maxPrice' => 50]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el juego recibido tiene el rango de precios correcto
        foreach ($data as $juego) {
            $this->assertGreaterThanOrEqual(20, $juego['precio'], "El precio es menor a 20");
            $this->assertLessThanOrEqual(50, $juego['precio'], "El precio es mayor a 50");
        }
    }
    
    Public function testObtenerJuegosDebajoDePrecio()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findBelowPrice', ['price' => 40]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que el juego recibido tiene la editorial madre correcta
        foreach ($data as $juego) {
            $this->assertLessThanOrEqual(20, $juego['precio'], "El precio es menor a 20");
        }
    }

    Public function testObtenerJuegosEncimaDePrecio()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findOverPrice', ['price' => 40]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        foreach ($data as $juego) {
            $this->assertGreaterThanOrEqual(20, $juego['precio'], "El precio es menor a 20");
        }
    }

    Public function testObtenerJuegoPorRangoDeJugadores()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByPlayesRange', ['minPlayers' => 2, 'maxPlayers' => 4]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        foreach ($data as $juego) {
            $this->assertGreaterThanOrEqual(2, $juego['minJugadores'], "Los jugadores son menos que 2");
            $this->assertLessThanOrEqual(4, $juego['maxJugadores'], "Los jugadores son más de 4");
        }
    }

    Public function testObtenerJuegosMinimoJugadores()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByMinPlayes', ['minJugadores' => 2]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        foreach ($data as $juego) {
            $this->assertGreaterThanOrEqual(2, $juego['minJugadores'], "Juego para menos de dos jugadores");
        }
    }

    Public function testObtenerJuegosMaximoJugadores()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
 
        $url = $this->getUrl('game_findByMaxPlayers', ['maxJugadores' => 4]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        foreach ($data as $juego) {
            $this->assertLessThanOrEqual(4, $juego['maxJugadores'], "Juego para mas de 4 jugadores");
        }
    }

    public function testObtenerJuegosPorAutor()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByAuthors', ['id' => 1]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los juegos
        $this->validateAutorStructure($data);

        // Validar que los juegos recibidos tienen el autor correcto
        foreach ($data as $juego) {
            $this->assertEquals(1, $juego['autores'][0]['id'], "Se encontró un juego con un autor distinto a 1");
        }
    }

    public function testObtenerJuegosSiAutoma()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByYesAutoma');

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los juegos
        $this->validateAutorStructure($data);

        // Validar que los juegos obtenidos tienen automa
        foreach ($data as $juego) {
            $this->assertEquals(true, $juego['dispAutoma'], "Se encontró un juego que no dispone de automa");
        }
    }

    public function testObtenerJuegosNoAutoma()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByNoAutoma');

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los juegos
        $this->validateAutorStructure($data);

        // Validar que los juegos obtenidos tienen automa
        foreach ($data as $juego) {
            $this->assertEquals(false, $juego['dispAutoma'], "Se encontró un juego que SI dispone de automa");
        }
    }

    public function testObtenerJuegosPorTipo()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findByType',['tipo' => 'Estrategia']);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los juegos
        $this->validateAutorStructure($data);

        // Validar que los juegos obtenidos tienen automa
        foreach ($data as $juego) {
            $this->assertEquals('Estrategia', $juego['tipo'], "Se encontró un juego que no es de tipo estrategia");
        }
    }

    public function testObtenerExpansionesPorJuego()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findExpansionsByGame', ['idJuego' => 2]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los juegos
        $this->validateAutorStructure($data);

        // Validar que cada expansión tenga el 'juegoBase' correcto
        foreach ($data as $juego) {
            $this->assertArrayHasKey('juegoBase', $juego, "El campo 'juegoBase' no está presente en el juego");
            $this->assertArrayHasKey('id', $juego['juegoBase'], "El campo 'id' del juego base no está presente");
            $this->assertEquals(2, $juego['juegoBase']['id'], "Se encontró un juego base con un id distinto a 2");
        }
    }

    public function testObtenerTodasLasExpansiones()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findAllExpansions');

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los juegos
        $this->validateAutorStructure($data);

        // Validar que cada juego es una expansión
        foreach ($data as $juego) {
            // Asegurarse de que cada juego tenga el campo baseExpansion y sea una expansión
            $this->assertArrayHasKey('baseExpansion', $juego, "El campo 'baseExpansion' no está presente en el juego");
            $this->assertEquals('expansion', $juego['baseExpansion'], "El campo 'baseExpansion' no tiene el valor esperado para un juego expansión");
    
            // Comprobar que el campo 'juegoBase' también está presente
            $this->assertArrayHasKey('juegoBase', $juego, "El campo 'juegoBase' no está presente en el juego");
            $this->assertArrayHasKey('id', $juego['juegoBase'], "El campo 'id' de juegoBase no está presente");
        }
    }

    public function testObtenerExpansionesConAutoma()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findExpansionsWithAutoma');

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los juegos
        $this->validateAutorStructure($data);

        // Validar que cada juego es una expansión
        foreach ($data as $juego) {
            // Asegurarse de que cada juego tenga el campo baseExpansion y sea una expansión
            $this->assertArrayHasKey('baseExpansion', $juego, "El campo 'baseExpansion' no está presente en el juego");
            $this->assertEquals('expansion', $juego['baseExpansion'], "El campo 'baseExpansion' no tiene el valor esperado para un juego expansión");
    
            // Comprobar que el campo 'juegoBase' también está presente
            $this->assertArrayHasKey('juegoBase', $juego, "El campo 'juegoBase' no está presente en el juego");
            $this->assertArrayHasKey('id', $juego['juegoBase'], "El campo 'id' de juegoBase no está presente");

            //Comprobar que el campo 'dispAutoma' es true
            $this->assertEquals(true, $juego['dispAutoma'], "El campo 'dispAutoma' no tiene el valor esperado para un juego con automa");
        }
    }

    public function testObtenerExpansionesSinAutoma()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('game_findExpansionsWithoutAutoma');

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de los juegos
        $this->validateAutorStructure($data);

        // Validar que cada juego es una expansión
        foreach ($data as $juego) {
            // Asegurarse de que cada juego tenga el campo baseExpansion y sea una expansión
            $this->assertArrayHasKey('baseExpansion', $juego, "El campo 'baseExpansion' no está presente en el juego");
            $this->assertEquals('expansion', $juego['baseExpansion'], "El campo 'baseExpansion' no tiene el valor esperado para un juego expansión");
    
            // Comprobar que el campo 'juegoBase' también está presente
            $this->assertArrayHasKey('juegoBase', $juego, "El campo 'juegoBase' no está presente en el juego");
            $this->assertArrayHasKey('id', $juego['juegoBase'], "El campo 'id' de juegoBase no está presente");

            //Comprobar que el campo 'dispAutoma' es true
            $this->assertEquals(false, $juego['dispAutoma'], "El campo 'dispAutoma' no tiene el valor esperado para un juego con automa");
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
        foreach ($data as $juego) {
            $this->assertArrayHasKey('id', $juego);
            $this->assertArrayHasKey('nombre', $juego);
            $this->assertArrayHasKey('baseExpansion', $juego);
            $this->assertArrayHasKey('tipo', $juego);
            $this->assertArrayHasKey('descripcion', $juego);
            $this->assertArrayHasKey('anioPublicacion', $juego);
            $this->assertArrayHasKey('dispAutoma', $juego);
            $this->assertArrayHasKey('editorialLocal', $juego);
            $this->assertArrayHasKey('editorialMadre', $juego);
            $this->assertArrayHasKey('precio', $juego);
            $this->assertArrayHasKey('minJugadores', $juego);
            $this->assertArrayHasKey('maxJugadores', $juego);
            $this->assertArrayHasKey('autores', $juego);
        }
    }

    // Método para verificar que el número de juegos coincide con la base de datos
    private function assertCountJuegos($client, array $data): void
    {
        $juegoRepository = $client->getContainer()->get(JuegoRepository::class);
        $numeroJuegosEnBD = count($juegoRepository->findAll());
        $this->assertCount($numeroJuegosEnBD, $data);
    }
}

