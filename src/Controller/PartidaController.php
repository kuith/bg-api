<?php

namespace App\Controller;
use App\Entity\Partida;
use App\Repository\JuegoRepository;
use App\Repository\JugadorRepository;
use App\Repository\PartidaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/matches')]

class PartidaController extends AbstractController
{
    #[Route('/', name: 'match_list', methods: ['GET', 'HEAD'])]
    public function index(PartidaRepository $repository): Response
    {
        $partidas = $repository->findAll();

        return $this->json($partidas, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
    }

    #[Route('/id/{id<\d+>}', name: 'match_show', methods: ['GET', 'HEAD'])]
    public function show(int $id, PartidaRepository $repository): Response
    {
        $partida = $repository->findMatchById($id);

        if (!$partida) {
            throw $this->createNotFoundException('Partida no encontrada');
        }

        //return $this->json($jugador);
        return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
    }

    #[Route('/date/{fecha}', name: 'match_findByDate', methods: ['GET', 'HEAD'])]
    public function findMatchByDate(String $fecha, PartidaRepository $repository): Response
    {
        $partidas = $repository->findMatchByDate($fecha);

        if (!$partidas) {
            throw $this->createNotFoundException('Partida no encontrada.');
        }

        return $this->json($partidas, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
    }

    #[Route('/winnersRanking', name: 'match_findWinnersRanking', methods: ['GET', 'HEAD'])]
    public function getWinnersRanking(PartidaRepository $repository): Response
    {
        $partida = $repository->getWinnersRanking();

        if (!$partida) {
            throw $this->createNotFoundException('Partida no encontrada.');
        }

        return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_ganador']);
    }

    #[Route('/player/{jugadorId}', name: 'match_findByPlayer', methods: ['GET', 'HEAD'])]
    public function findMatchByPlayer(String $jugadorId, PartidaRepository $repository): Response
    {
        $partida = $repository->findMatchByPlayer($jugadorId);

        if (!$partida) {
            throw $this->createNotFoundException('Partidas no encontradas para ese jugador.' .$jugadorId);
        }

        return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_jugador']);
    }

    #[Route('/', name: 'match_create', methods: ['POST'])]
    public function createMatch(
        Request $request,
        EntityManagerInterface $em,
        JuegoRepository $juegoRepository,
        JugadorRepository $jugadorRepository
    ): JsonResponse{

        $data = json_decode($request->getContent(), true);

        // Intenta obtener los datos JSON
        try {
            $data = $request->toArray();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Formato de datos inválido'], 400);
        }

        //Verificar los campos requeridos
        $requiredFields = ['fecha', 'juego_id', 'jugadores_ids', 'ganadores_ids'];
        $missingFields = [];

        // Verificar si los campos requeridos están presentes
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $missingFields[] = $field;  // Agregar el campo faltante a la lista
            }
        }

        // Si hay campos faltantes, devolver un mensaje detallado
        if (count($missingFields) > 0) {
            $missingFieldsString = implode(", ", $missingFields);
            return new JsonResponse("Faltan los siguientes datos: {$missingFieldsString}", 400);
        }

        $partida = new Partida();
        $partida->setFecha(new \DateTime($data['fecha']));

        // Establecer jugadores
        foreach ($data['jugadores_ids'] as $jugadorId) {
            $jugador = $jugadorRepository->find($jugadorId);
            if (!$jugador) {
                throw $this->createNotFoundException('Jugador no encontrado');
            }
        $partida->getJugadores()->add($jugador);

        }

        // Establecer ganadores
        foreach ($data['ganadores_ids'] as $ganadorId) {
            $jugador = $jugadorRepository->find($ganadorId);
            if (!$jugador) {
                throw $this->createNotFoundException('Jugador ganador no encontrado');
            }
            $partida->getGanadores()->add($jugador);
        }

        //Establecer juego
        foreach ($data['juego_id'] as $juegoId) {
            $juego = $juegoRepository->find($juegoId);

            if (!$juego) {
                return new JsonResponse(['error' => "Juego con ID $juegoId no encontrado"], Response::HTTP_NOT_FOUND);
            }

            $partida->setJuego($juego);
        }

        $em->persist($partida);
        $em->flush();

        return new JsonResponse(['mensaje' => 'Partida creada correctamente'], 201);
    }

    #[Route('/{id}', name: 'match_update', methods: ['PATCH'])]
    public function actualizarPartida(int $id, Request $request, PartidaRepository $partidaRepository, EntityManagerInterface $em): JsonResponse
    {
        $partida = $partidaRepository->find($id);

        if (!$partida) {
            return new JsonResponse(['error' => 'Autor no encontrado'], 404);
        }

        // Obtener datos del request
        $data = json_decode($request->getContent(), true);

        // Modificar solo si los valores existen en la petición
        if (isset($data['fecha'])) {
            $partida->setFecha(new \DateTime($data['fecha']));
        }
        if (isset($data['juego_id'])) {
            $partida->setJuego($data['juego_id']);
        }
        if (isset($data['jugadores_ids'])) {
            $partida->setJugadores($data['jugadores_ids']);
        }
        if (isset($data['ganadores_ids'])) {
            $partida->setGanadores($data['ganadores_ids']);
        }
        
        // Guardar cambios en la base de datos
        $em->flush();

        return new JsonResponse(['message' => 'Partida actualizada con éxito'], 200);
    }

    #[Route('/{id<\d+>}', name: 'match_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $partida = $entityManager->getRepository(Partida::class)->find($id);

        if (!$partida) {
            throw $this->createNotFoundException(
                'Partida no encontrado: '.$id
            );
        }

        $entityManager->remove($partida);
        $entityManager->flush();
            
        return new Response('Partida eliminada!', 200);
    }

     #[Route('/gamesByPlayer/{jugadorId}', name: 'matches_findGamesByPlayer', methods: ['GET', 'HEAD'])]
        public function juegosPorJugador(String $jugadorId, PartidaRepository $repository): Response
    {
        $partidas = $repository->findMatchByPlayer($jugadorId);

        if (!$partidas) {
            throw $this->createNotFoundException('Partidas no encontradas para ese jugador.' .$jugadorId);
        }

        // Extraer los juegos únicos de las partidas
        $juegos = [];
        foreach ($partidas as $partida) {
            $juegos[] = $partida->getJuego();
        }

        // Eliminar duplicados
        $juegosUnicos = array_unique($juegos, SORT_REGULAR);
        return $this->json($juegosUnicos, Response::HTTP_OK, [], ['groups' => 'jugador_juegos']);
    }

    #[Route('/playersByGame/{juegoId}', name: 'matches_findPlayersByGame', methods: ['GET', 'HEAD'])]
        public function findPlayersByGame(String $juegoId, PartidaRepository $repository): Response
    {
    $partidas = $repository->findPlayersByGame($juegoId);

        if (!$partidas) {
            throw $this->createNotFoundException('Partidas no encontradas para ese juego.' . $juegoId);
        }

        // Extraer los jugadores únicos de las partidas
        $jugadores = [];
        foreach ($partidas as $partida) {
            foreach ($partida->getJugadores() as $jugador) {
                $jugadores[$jugador->getId()] = $jugador;
            }
        }

        // Convertir a array y eliminar duplicados
        $jugadoresUnicos = array_values($jugadores);
        return $this->json($jugadoresUnicos, Response::HTTP_OK, [], ['groups' => 'jugador_juegos']);
    }

    #[Route('/winnersByGame/{juegoId}', name: 'matches_findWinnersByGame', methods: ['GET', 'HEAD'])]
        public function findGanadoresByJuegoId(String $juegoId, PartidaRepository $repository): Response
    {
    $partidas = $repository->findWinnersByJuego($juegoId);

        if (!$partidas) {
            throw $this->createNotFoundException('Partidas no encontradas para ese juego.' . $juegoId);
        }

        // Extraer los jugadores únicos de las partidas
        $ganadores = [];
        foreach ($partidas as $partida) {
            foreach ($partida->getGanadores() as $ganador) {
                $ganadores[$ganador->getId()] = $ganador;
            }
        }

        // Convertir a array y eliminar duplicados
        $ganadoresUnicos = array_values($ganadores);
        return $this->json($ganadoresUnicos, Response::HTTP_OK, [], ['groups' => 'jugador_juegos']);
    }
}