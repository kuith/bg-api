<?php

namespace App\Controller;
use App\Entity\Jugador;
use App\Repository\JugadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/jugadores')]

class JugadorController extends AbstractController
{
    #[Route('/', name: 'app_jugador_getAll', methods: ['GET'])]
    public function getAll(JugadorRepository $repository): Response
    {
        $jugadores = $repository->findAll();

        return $this->json($jugadores);
    }

    #[Route('/{id<\d+>}', name: 'app_jugador_getById', methods: ['GET'])]
    public function getById(int $id, JugadorRepository $repository): Response
    {
        $jugador = $repository->findOneById($id);

        if (!$jugador) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($jugador);
    }

    #[Route('/', name: 'app_jugador_crearJugador', methods: ['POST'])]
    public function crearJugador (Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $jugador = new Jugador();
        $jugador->setNombre($request->get('nombre'));
        $jugador->setNick($request->get('nick'));
        $jugador->setEmail($request->get('email'));
        $jugador->setPassword($request->get('password'));
        $jugador->setRol($request->get('rol'));

        if (!$jugador->getNombre() || !$jugador->getemail() || !$jugador->getPassword()) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }
   
        $entityManager->persist($jugador);
        $entityManager->flush();
    
        $data =  [
            'id' => $jugador->getId(),
            'nombre' => $jugador->getNombre(),
            'nick' => $jugador->getNick(),
            'email' => $jugador->getEmail(),
            'password' => $jugador->getPassword(),
            'rol' => $jugador->getRol()
        ];
            
        return $this->json($data, 200);
    }
}
