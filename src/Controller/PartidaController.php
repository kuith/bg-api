<?php

namespace App\Controller;
use App\Entity\Juego;
use App\Entity\Jugador;
use App\Entity\Partida;
use App\Repository\PartidaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/partidas')]

class PartidaController extends AbstractController
{
    #[Route('/', name: 'app_partida_getAll', methods: ['GET'])]
    public function getAll(PartidaRepository $repository): Response
    {
        $partidas = $repository->findAll();

        return $this->json($partidas, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
    }

    #[Route('/search/{id<\d+>}', name: 'app_partida_getById', methods: ['GET'])]
    public function getById(int $id, PartidaRepository $repository): Response
    {
        $partida = $repository->findOneById($id);

        if (!$partida) {
            throw $this->createNotFoundException('Partida no encontrada');
        }

        //return $this->json($jugador);
        return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
    }

    #[Route('/search/fecha/{fecha}', name: 'app_partida_getByFecha', methods: ['GET'])]
    public function findByFecha(String $fecha, PartidaRepository $repository): Response
    {
        $partida = $repository->findByFecha($fecha);

        if (!$partida) {
            throw $this->createNotFoundException('Partida no encontrada.');
        }

        return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
    }

    #[Route('/search/rankingGanadores', name: 'app_partida_getRanking', methods: ['GET'])]
    public function getRankingDeGanadores(PartidaRepository $repository): Response
    {
        $partida = $repository->getRankingDeGanadores();

        if (!$partida) {
            throw $this->createNotFoundException('Partida no encontrada.');
        }

        return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_ganador']);
    }

    #[Route('/search/jugador/{jugadorId}', name: 'app_partida_getByJugadorId', methods: ['GET'])]
    public function findByJugador(String $jugadorId, PartidaRepository $repository): Response
    {
        $partida = $repository->findByJugador($jugadorId);

        if (!$partida) {
            throw $this->createNotFoundException('Partidas no encontradas para ese jugador.' .$jugadorId);
        }

        return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_jugador']);
    }

    #[Route('/', name: 'crear_partida', methods: ['POST'])]
    public function crearPartida(Request $request, EntityManagerInterface $em): JsonResponse
    {
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
            $jugador = $em->getRepository(Jugador::class)->find($jugadorId);
            if (!$jugador) {
                throw $this->createNotFoundException('Jugador no encontrado');
            }
        $partida->getJugadores()->add($jugador);

        }

        // Establecer ganadores
        foreach ($data['ganadores_ids'] as $ganadorId) {
            $jugador = $em->getRepository(Jugador::class)->find($ganadorId);
            if (!$jugador) {
                throw $this->createNotFoundException('Jugador ganador no encontrado');
            }
            $partida->getGanadores()->add($jugador);
        }

        //Establecer juego
        foreach ($data['juego_id'] as $juegoId) {
            $juego = $em->getRepository(Juego::class)->findOneById($juegoId);

            if (!$juego) {
                return new JsonResponse(['error' => "Juego con ID $juegoId no encontrado"], Response::HTTP_NOT_FOUND);
            }

            $partida->setJuego($juego);
        }

        $em->persist($partida);
        $em->flush();

        return new JsonResponse(['mensaje' => 'Partida creada correctamente'], 201);
    }

    #[Route('/{id<\d+>}', name: 'partida_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $partida = $entityManager->getRepository(Partida::class)->findOneById($id);

        if (!$partida) {
            throw $this->createNotFoundException(
                'Partida no encontrado: '.$id
            );
        }

        $entityManager->remove($partida);
        $entityManager->flush();
            
        return new Response('Partida eliminada!', 200);
    }

     #[Route('/search/juegosPorJugador/{jugadorId}', name: 'app_partida_getJuegosPorJugador', methods: ['GET'])]
        public function juegosPorJugador(String $jugadorId, PartidaRepository $repository): Response
    {
        $partidas = $repository->findByJugador($jugadorId);

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
}