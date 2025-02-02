<?php

namespace App\Controller;
use App\Entity\Jugador;
use App\Repository\JugadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/jugadores')]

class JugadorController extends AbstractController
{
    #[Route('/', name: 'app_jugador_getAll', methods: ['GET'])]
    public function getAll(JugadorRepository $repository): Response
    {
        $jugadores = $repository->findAll();

        //return $this->json($jugadores);
        return $this->json($jugadores, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }

    #[Route('/search/{id<\d+>}', name: 'app_jugador_getById', methods: ['GET'])]
    public function getById(int $id, JugadorRepository $repository): Response
    {
        $jugador = $repository->findOneById($id);

        if (!$jugador) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        //return $this->json($jugador);
        return $this->json($jugador, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }

    #[Route('/search/nombre/{nombre}', name: 'app_jugador_getByNombre', methods: ['GET'])]
    public function getByNombre(String $nombre, JugadorRepository $repository): Response
    {
        $jugador = $repository->findOneByNombre($nombre);

        if (!$jugador) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($jugador, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }

    #[Route('/search/correo/{correo}', name: 'app_jugador_getByCorreo', methods: ['GET'])]
    public function getByEmail(String $correo, JugadorRepository $repository): Response
    {
        $jugador = $repository->findOneByEmail($correo);

        if (!$jugador) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($jugador, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }

    #[Route('/search/rol/{rol}', name: 'app_jugador_getByRol', methods: ['GET'])]
    public function getByRol(String $rol, JugadorRepository $repository): Response
    {
        $jugadores = $repository->findByRol($rol);

        if (!$jugadores) {
            throw $this->createNotFoundException('Rol no encontrado');
        }

        return $this->json($jugadores, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }
    #[Route('/search/playersByGame/{juegoId}', name: 'app_jugador_getplayersByGame', methods: ['GET'])]
    public function getplayersByGame(int $juegoId, JugadorRepository $repository): Response
    {
        $jugadores = $repository->findPlayersByGame($juegoId);

        if (!$jugadores) {
            throw $this->createNotFoundException('Juego no encontrado o no ha sido jugado');
        }

        return $this->json($jugadores, Response::HTTP_OK, [], ['groups' => 'jugador_juegos']);
    }

    #[Route('/search/ganadas/{idJugador}', name: 'app_jugador_getByGanadasPorId', methods: ['GET'])]
    public function getByGanadas(String $idJugador, JugadorRepository $repository): Response
    {
        $partidasGanadas = $repository->findPartidasGanadasPorJugador($idJugador);

        if (!$partidasGanadas) {
            throw $this->createNotFoundException('Jugador no encontrado o no ha ganado partidas');
        }

        //return $this->json($partidas);
        return $this->json($partidasGanadas, Response::HTTP_OK, [], ['groups' => 'jugador_ganadas']);
    }

    #[Route('/', name: 'jugador_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Intenta obtener los datos JSON
        try {
            $data = $request->toArray();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Formato de datos inválido'], 400);
        }

        // Validar que los campos requeridos están presentes
        if (!isset($data['nombre']) || !isset($data['password']) || !isset($data['correo']) || !isset($data['rol'])) {
            return new JsonResponse($data, 400);
        }

        $jugador = new Jugador();
        $jugador->setNombre($data['nombre']);
        $jugador->setCorreo($data['correo']);
        $jugador->setRol($data['rol']);
        $jugador->setFechaRegistro(new \DateTime()); // Asigna la fecha actual como fecha de registro
        $jugador->setPassword($data['password']);

        // Validar el objeto Jugador (incluyendo las restricciones en correo, rol, etc.)
        $errors = $validator->validate($jugador);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        // Guardar el jugador en la base de datos
        $em->persist($jugador);
        $em->flush();

        return new JsonResponse(['message' => 'Jugador creado con éxito'], 201);
    }

    
    #[Route('/{id<\d+>}', name: 'jugador_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $jugador = $entityManager->getRepository(Jugador::class)->findOneById($id);

        if (!$jugador) {
            throw $this->createNotFoundException(
                'Jugador no encontrado: '.$id
            );
        }

        $entityManager->remove($jugador);
        $entityManager->flush();
            
        return new Response('Jugador eliminado!', 200);
    }

    #[Route('/jugador/{id}', name: 'actualizar_jugador', methods: ['PATCH'])]
public function actualizarJugador(int $id, Request $request, JugadorRepository $jugadorRepository, EntityManagerInterface $em): JsonResponse
{
    $jugador = $jugadorRepository->find($id);

    if (!$jugador) {
        return new JsonResponse(['error' => 'Jugador no encontrado'], 404);
    }

    // Obtener datos del request
    $data = json_decode($request->getContent(), true);

    // Modificar solo si los valores existen en la petición

    if (isset($data['nombre'])) {
        $jugador->setNombre($data['nombre']);
    }
    if (isset($data['password'])) {
        $jugador->setPassword($data['password']);
    }
    if (isset($data['correo'])) {
        $jugador->setCorreo($data['correo']);
    }
    if (isset($data['rol'])) {
        $jugador->setRol($data['rol']);
    }
    if (isset($data['fecha_registro'])) {
        $jugador->setFechaRegistro(new \DateTime($data['fecha_registro']));
    }

    // Guardar cambios en la base de datos
    $em->flush();

    return $this->json($jugador, 200);
}


    
}
