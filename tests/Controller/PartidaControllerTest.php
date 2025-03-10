<?php
namespace App\Tests\Controller;
use App\Repository\PartidaRepository;

class PartidaControllerTest extends BaseWebTestCase
{
    protected $client;
    public function testObtenerListaPartidas()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('match_list');

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar la estructura de las partidas
        $this->validatePartidaStructure($data);

        // Comparar el número de partidas
        $this->assertCountPartidas($client, $data);
    }

    public function testObtenerPartidasPorId()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('match_show', ['id' => 1]);

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que la partida recibida tiene el ID correcto
        $this->assertEquals(1, $data['id']); // Verificamos que el ID sea el que pedimos
    }

    public function testObtenerPartidasPorFecha()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;

        $fecha = ('2021-01-01 00:00:00');
        $url = $this->getUrl('match_findByDate', ['fecha' => $fecha]);
       

        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);

        // Verificar la respuesta
        $this->VerifyResponse($client);

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que la partida recibida tiene la fecha correcta
        if (!empty($data)) {
           $fechaRespuesta = (new \DateTime($data[0]['fecha']))->format('Y-m-d 00:00:00');
           $this->assertEquals($fecha, $fechaRespuesta);
        } else {
            $this->fail('No se encontraron partidas para la fecha especificada.');
        }
    }

    public function testObtenerRankingGanadores()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;

        $url = $this->getUrl('match_findWinnersRanking');
  
        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);
        

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que la partida recibida tiene la fecha correcta
        //$this->assertEquals('2021-01-01', $data[0]['fecha']);
    }

    public function testObtenerPartidasPorJugador()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;

        $url = $this->getUrl('match_findByPlayer', ['jugadorId' => 1]);
       
        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);
        

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        // Validar que las partidas recibidas tienen al jugador deseado
        foreach ($data as $partida) {
            $this->assertArrayHasKey('jugadores', $partida, "El campo 'jugadores' no está presente en la partida");
            $this->assertContains(1, array_column($partida['jugadores'], 'id'), "El jugador con ID 1 no está presente en la partida");
        }
    }

    public function testObtenerJuegosPorJugador() //Los juegos a los que ha jugado un jugador
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;

        $url = $this->getUrl('matches_findGamesByPlayer', ['jugadorId' => 1]);
       
        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);
        

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        foreach ($data as $juego) {
            $this->assertArrayHasKey('id', $juego, "El campo 'id' no está presente en el juego");
            $this->assertArrayHasKey('nombre', $juego, "El campo 'nombre' no está presente en el juego");
        }
    }

    public function testObtenerJugadoresPorJuego() //Los juegos a los que ha jugado un jugador
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;

        $url = $this->getUrl('matches_findPlayersByGame', ['juegoId' => 1]);
       
        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);
        

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        foreach ($data as $jugador) {
            $this->assertArrayHasKey('id', $jugador, "El campo 'id' no está presente en el juego");
            $this->assertArrayHasKey('nombre', $jugador, "El campo 'nombre' no está presente en el juego");
        }
    }

    public function testObtenerGanadoresPorJuego() //Los juegos a los que ha jugado un jugador
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;

        $url = $this->getUrl('matches_findWinnersByGame', ['juegoId' => 1]);
       
        // Hacer la solicitud GET
        $client->request('GET', $url, [], [], ['HTTP_X-DEBUG' => '1']);
        
        // Verificar la respuesta
        $this->VerifyResponse($client);
        

        // Verificar que la respuesta tenga la estructura correcta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->verifyReponseExtrucre($client, $data);

        foreach ($data as $jugador) {
            $this->assertArrayHasKey('id', $jugador, "El campo 'id' no está presente en el juego");
            $this->assertArrayHasKey('nombre', $jugador, "El campo 'nombre' no está presente en el juego");
        }
    }

/*     public function testCrearPartida()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('match_create');
        
        // Obtener el entity manager y los repositorios
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $jugadorRepository = $entityManager->getRepository(Jugador::class);
        $juegoRepository = $entityManager->getRepository(Juego::class);

        // Obtener las referencias de los jugadores y el juego
        $jugador1 = $jugadorRepository->findOneBy(['nombre' => 'Rafa']);
        $jugador2 = $jugadorRepository->findOneBy(['nombre' => 'Paula']);
        $jugador3 = $jugadorRepository->findOneBy(['nombre' => 'Miguel']);
        $jugador4 = $jugadorRepository->findOneBy(['nombre' => 'Irene']);
        $juego = $juegoRepository->find(['id' => '1']);
        //$fecha = (new \DateTime('2021-01-01 00:00:00'))->format('Y-m-d 00:00:00');
        $fecha = '2021-01-01 00:00:00';

        //datos validos
        $partidaData = [
            "fecha" => $fecha,
            "jugadores_ids" => [$jugador1->getId(), $jugador2->getId(), $jugador3->getId(), $jugador4->getId()],
            "ganadores_ids" => [$jugador2->getId()],
            "juego_id" => $juego ->getId()
        ];

        dump($juego); // Depurar si se está obteniendo correctamente el juego
        dump($juego ? $juego->getId() : 'Juego no encontrado');

        // Hacer la solicitud GET
        $client->request('POST', $url, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($partidaData, JSON_THROW_ON_ERROR));

        // Verificar que la respuesta sea 201 (Created)
        $this->assertEquals($client->getResponse()->getStatusCode(), 201);
        
        // Verificar el contenido de la respuesta
        $data = json_decode($client->getResponse()->getContent(), true);
        dump($data); // Verifica el contenido de la respuesta
        $this->assertEquals('Partida creada correctamente', $data['message']);

    } */

    public function testActualizarPartida()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('match_update', ['id' => 1]);

        //datos validos
        $partidaData = [
            "fecha" => "2025-03-01 12:00:00"
        ];

        // Hacer la solicitud GET
        $client->request('PATCH', $url, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($partidaData));

        // Verificar que la respuesta sea 200 (Created)
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        

        // Verificar el contenido de la respuesta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Partida actualizada con éxito', $data['message']);

    }

    public function testBorrarPartida()
    {
        // Crear el cliente y obtener la URL generada para el endpoint
        $client = $this->client;
        $url = $this->getUrl('match_delete', ['id' => 1]);

        // Hacer la solicitud GET
        $client->request('DELETE', $url, [], [], ['CONTENT_TYPE' => 'application/json']);

        // Verificar que la respuesta sea 201 (Created)
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        

        // Verificar el contenido de la respuesta
        $this->assertEquals('Partida eliminada!', $client->getResponse()->getContent());
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
    private function validatePartidaStructure(array $data): void
    {
        foreach ($data as $partida) {
            $this->assertArrayHasKey('id', $partida);
            $this->assertArrayHasKey('jugadores', $partida);
            $this->assertArrayHasKey('ganadores', $partida);
            $this->assertArrayHasKey('juego', $partida);
            $this->assertArrayHasKey('fecha', $partida);
        }
    }

    // Método para verificar que el número de partidas coincide con la base de datos
    private function assertCountPartidas($client, array $data): void
    {
        $partidaRepository = $client->getContainer()->get(PartidaRepository::class);
        $numeroPartidasEnBD = count($partidaRepository->findAll());
        $this->assertCount($numeroPartidasEnBD, $data);
    }
}

