<?php

namespace App\Controller;
use App\Entity\Jugador;
use App\Repository\JugadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api/jugadores')]

class JugadorController extends AbstractController
{
    #[Route('/', name: 'app_jugador_getAll', methods: ['GET'])]
    public function getAll(JugadorRepository $repository): Response
    {
        $jugadores = $repository->findAll();

        return $this->json($jugadores);
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

        return $this->json($jugadores);
    }

    #[Route('/', name: 'app_jugador_crearJugador', methods: ['POST'])]
    public function crearJugador (Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $jugador = new Jugador();
        $jugador->setNombre($request->get('nombre'));
        $jugador->setCorreo($request->get('correo'));
        $jugador->setPassword($request->get('password'));
        $jugador->setRol($request->get('rol'));
        $jugador->setFechaRegistro($request->get('fecharegistro'));

        if (!$jugador->getNombre() || !$jugador->getCorreo() || !$jugador->getPassword()) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }
   
        $entityManager->persist($jugador);
        $entityManager->flush();
    
        $data =  [
            'id' => $jugador->getId(),
            'nombre' => $jugador->getNombre(),
            'correo' => $jugador->getCorreo(),
            'password' => $jugador->getPassword(),
            'rol' => $jugador->getRol(),
            'fecharegistro' => $jugador->getFechaRegistro()
        ];
            
        return $this->json($data, 200);
    }

    #[Route('/{id<\d+>}', name: 'jugador_edit', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $jugador = $entityManager->getRepository(Jugador::class)->findOneById($id);

        if (!$jugador) {
            throw $this->createNotFoundException(
                'Jugador no encontrado: '.$id
            );
        }

        $jugador->setNombre($request->get('nombre'));
        $jugador->setCorreo($request->get('correo'));
        $jugador->setPassword($request->get('password'));
        $jugador->setRol($request->get('rol'));
        $jugador->setFechaRegistro($request->get('fecharegistro'));
        $entityManager->flush();

        $data =  [
            'id' => $jugador->getId(),
            'nombre' => $jugador->getNombre(),
            'correo' => $jugador->getCorreo(),
            'password' => $jugador->getPassword(),
            'rol' => $jugador->getRol(),
            'fecharegistro' => $jugador->getFechaRegistro()
        ];
            
        return $this->json($data, 200);
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