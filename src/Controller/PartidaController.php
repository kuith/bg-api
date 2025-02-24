<?php

namespace App\Controller;
use App\Entity\Juego;
use App\Entity\Jugador;
use App\Entity\Partida;
use App\Repository\AutorRepository;
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
    #[Route('/', name: 'match_list', methods: ['GET'])]
    public function index(PartidaRepository $repository): Response
    {
        $partidas = $repository->findAll();

        return $this->json($partidas, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
    }

    #[Route('/id/{id<\d+>}', name: 'match_show', methods: ['GET'])]
    public function show(int $id, PartidaRepository $repository): Response
    {
        $partida = $repository->findMatchById($id);

        if (!$partida) {
            throw $this->createNotFoundException('Partida no encontrada');
        }

        //return $this->json($jugador);
        return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
    }

    #[Route('/date/{fecha}', name: 'match_findByDate', methods: ['GET'])]
    public function findMatchByDate(String $fecha, PartidaRepository $repository): Response
    {
        $partidas = $repository->findMatchByDate($fecha);

        if (!$partidas) {
            throw $this->createNotFoundException('Partida no encontrada.');
        }

        return $this->json($partidas, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
    }

    #[Route('/winnersRanking', name: 'match_findWinnersRanking', methods: ['GET'])]
    public function getWinnersRanking(PartidaRepository $repository): Response
    {
        $partida = $repository->getWinnersRanking();

        if (!$partida) {
            throw $this->createNotFoundException('Partida no encontrada.');
        }

        return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_ganador']);
    }

    #[Route('/player/{jugadorId}', name: 'match_findByPlayer', methods: ['GET'])]
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

        // Validar que los campos requeridos están presentes
        if (!isset($data['fecha']) || !isset($data['juego_id']) || !isset($data['jugadores_ids']) || !isset($data['ganadores_ids'])) {
            return new JsonResponse($data, 400);
        }

        $partida = new Partida();
        $partida->setFecha(new \DateTime($data['fecha']));

        // Establecer jugadores
        foreach ($data['jugadores_ids'] as $jugadorId) {
            $jugador = $jugadorRepository->findPlayerById($jugadorId);
            if (!$jugador) {
                throw $this->createNotFoundException('Jugador no encontrado');
            }
        $partida->getJugadores()->add($jugador);

        }

        // Establecer ganadores
        foreach ($data['ganadores_ids'] as $ganadorId) {
            $jugador = $jugadorRepository->findPlayerById($ganadorId);
            if (!$jugador) {
                throw $this->createNotFoundException('Jugador ganador no encontrado');
            }
            $partida->getGanadores()->add($jugador);
        }

        //Establecer juego
        foreach ($data['juego_id'] as $juegoId) {
            $juego = $juegoRepository->findGameById($juegoId);

            if (!$juego) {
                return new JsonResponse(['error' => "Juego con ID $juegoId no encontrado"], Response::HTTP_NOT_FOUND);
            }

            $partida->setJuego($juego);
        }

        $em->persist($partida);
        $em->flush();

        return new JsonResponse(['mensaje' => 'Partida creada correctamente'], 201);
    }

    #[Route('/{id<\d+>}', name: 'match_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $partida = $entityManager->getRepository(Partida::class)->findMatchById($id);

        if (!$partida) {
            throw $this->createNotFoundException(
                'Partida no encontrado: '.$id
            );
        }

        $entityManager->remove($partida);
        $entityManager->flush();
            
        return new Response('Partida eliminada!', 200);
    }

     #[Route('/gamesByPlayer/{jugadorId}', name: 'matches_findGamesByPlayer', methods: ['GET'])]
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

    #[Route('/playersByGame/{juegoId}', name: 'matches_findPlayersByGame', methods: ['GET'])]
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

    #[Route('/winnersByGame/{juegoId}', name: 'matches_findWinnersByGame', methods: ['GET'])]
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