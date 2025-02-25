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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/players')]

class JugadorController extends AbstractController
{
    #[Route('/', name: 'player_list', methods: ['GET'])]
    public function index(JugadorRepository $repository): Response
    {
        $jugadores = $repository->findAll();

        return $this->json($jugadores, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }

    #[Route('/id/{id<\d+>}', name: 'player_show', methods: ['GET'])]
    public function show(int $id, JugadorRepository $repository): Response
    {
        $jugador = $repository->findPlayerById($id);

        if (!$jugador) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($jugador, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }

    #[Route('/name/{nombre}', name: 'player_findByName', methods: ['GET'])]
    public function findByName(String $nombre, JugadorRepository $repository): Response
    {
        $jugador = $repository->findPlayerByName($nombre);

        if (!$jugador) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($jugador, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }

    #[Route('/mail/{correo}', name: 'player_findByEmail', methods: ['GET'])]
    public function findByEmail(String $correo, JugadorRepository $repository): Response
    {
        $jugador = $repository->findPlayerByEmail($correo);

        if (!$jugador) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($jugador, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }

    #[Route('/rol/{rol}', name: 'player_findByRol', methods: ['GET'])]
    public function findByRol(String $rol, JugadorRepository $repository): Response
    {
        $jugadores = $repository->findPlayerByRol($rol);

        if (!$jugadores) {
            throw $this->createNotFoundException('Rol no encontrado');
        }

        return $this->json($jugadores, Response::HTTP_OK, [], ['groups' => 'jugador_lista']);
    }
    #[Route('/games/{juegoId}/players', name: 'players_findByGame', methods: ['GET'])]
    public function findPlayersByGame(int $juegoId, JugadorRepository $repository): Response
    {
        $jugadores = $repository->findPlayersByGame($juegoId);

        if (!$jugadores) {
            throw $this->createNotFoundException('Juego no encontrado o no ha sido jugado');
        }

        return $this->json($jugadores, Response::HTTP_OK, [], ['groups' => 'jugador_juegos']);
    }

    #[Route('/{idJugador}/matches/won', name: 'playar_findByWonMatches', methods: ['GET'])]
    public function findPartidasGanadas(String $idJugador, JugadorRepository $repository): Response
    {
        $partidasGanadas = $repository->findPartidasGanadasPorJugador($idJugador);

        if (!$partidasGanadas) {
            throw $this->createNotFoundException('Jugador no encontrado o no ha ganado partidas');
        }

        //return $this->json($partidas);
        return $this->json($partidasGanadas, Response::HTTP_OK, [], ['groups' => 'jugador_ganadas']);
    }

    #[Route('/', name: 'player_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Intenta obtener los datos JSON
        try {
            $data = $request->toArray();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Formato de datos inválido'], 400);
        }

        //Verificar los campos requeridos
        $requiredFields = ['nombre', 'password', 'correo', 'rol', 'fecha_registro'];
  
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

        $jugador = new Jugador();
        $jugador->setNombre($data['nombre']);
        $jugador->setCorreo($data['correo']);
        $jugador->setRol($data['rol']);
        $jugador->setFechaRegistro(new \DateTime()); // Asigna la fecha actual como fecha de registro
        // Hashear la contraseña
        $hashedPassword = $passwordHasher->hashPassword($jugador, $data['password']);
        $jugador->setPassword($hashedPassword);
        //$jugador->setPassword($data['password']);

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

    
    #[Route('/{id<\d+>}', name: 'player_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $jugador = $entityManager->getRepository(Jugador::class)->find($id);

        if (!$jugador) {
            throw $this->createNotFoundException(
                'Jugador no encontrado: '.$id
            );
        }

        $entityManager->remove($jugador);
        $entityManager->flush();
            
        return new Response('Jugador eliminado!', 200);
    }

    #[Route('/{id}', name: 'player_update', methods: ['PATCH'])]
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

        return new JsonResponse(['message' => 'Jugador actualizado con éxito'], 200);
    }
   
}
