<?php

namespace App\Controller;
use App\Entity\Jugador;
use App\Repository\JugadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
//use Symfony\Component\Validator\Constraints as Assert;


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

        return $this->json($jugador);
    }

    #[Route('/search/nombre/{nombre}', name: 'app_jugador_getByNombre', methods: ['GET'])]
    public function getByNombre(String $nombre, JugadorRepository $repository): Response
    {
        $jugador = $repository->findOneByNombre($nombre);

        if (!$jugador) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($jugador);
    }

    #[Route('/search/email/{email}', name: 'app_jugador_getByEmail', methods: ['GET'])]
    public function getByEmail(String $email, JugadorRepository $repository): Response
    {
        $jugador = $repository->findOneByEmail($email);

        if (!$jugador) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($jugador);
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

    #[Route('/search/ganadas/{id}', name: 'app_jugador_getByGanadasPorId', methods: ['GET'])]
    public function getByGanadas(String $id, JugadorRepository $repository): Response
    {
        $partidasGanadas = $repository->findPartidasGanadasPorJugador($id);

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

    
}
