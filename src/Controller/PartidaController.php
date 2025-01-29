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
        //$partida->setJuego($data['juego']);

        /* // Establecer ganador
       foreach ($data['ganador_id'] as $ganadorId) {
            $ganador = $em->getRepository(Jugador::class)->findOneById($ganadorId);

            if (!$ganador) {
                return new JsonResponse(['error' => "Jugador ganador con ID $ganadorId no encontrado"], Response::HTTP_NOT_FOUND);
            }

            $partida->setGanador($ganador);
        } */

        // Establecer ganadores
        foreach ($data['ganadores_ids'] as $ganadorId) {
            $jugador = $em->getRepository(Jugador::class)->find($ganadorId);
            if ($jugador) {
                $partida->getGanadores()->add($jugador);
                //$jugador->getPartidas()->add($partida); // Relación inversa
            }
        }

        // Establecer jugadores
        foreach ($data['jugadores_ids'] as $jugadorId) {
            $jugador = $em->getRepository(Jugador::class)->find($jugadorId);
            if ($jugador) {
                $partida->getJugadores()->add($jugador);
                //$jugador->getPartidas()->add($partida); // Relación inversa
            }
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
        //return $this->json($partida, Response::HTTP_OK, [], ['groups' => 'partida_lista']);
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

}