<?php

namespace App\Controller;
use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api/juegos')]

class JuegoController extends AbstractController
{
    #[Route('/', name: 'app_juego_getAll', methods: ['GET'])]
    public function getAll(JuegoRepository $repository): Response
    {
        $juegos = $repository->findAll();

        return $this->json($juegos);
    }

    #[Route('/search/{id<\d+>}', name: 'app_juegos_getById', methods: ['GET'])]
    public function getById(int $id, JuegoRepository $repository): Response
    {
        $juego = $repository->findOneById($id);

        if (!$juego) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($juego);
    }

    #[Route('/search/nombre/{nombre}', name: 'app_juego_getByNombre', methods: ['GET'])]
    public function getByNombre(String $nombre, JuegoRepository $repository): Response
    {
        $juego = $repository->findOneByNombre($nombre);

        if (!$juego) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        return $this->json($juego);
    }
    
    #[Route('/automa', name: 'app_juego_getAllAutoma', methods: ['GET'])]
    public function getAllAutoma(JuegoRepository $repository): Response
    {
        $juegos = $repository->findByAutoma();

        return $this->json($juegos);
    }

    #[Route('/noautoma', name: 'app_juego_getNoAllAutoma', methods: ['GET'])]
    public function getAllNoAutoma(JuegoRepository $repository): Response
    {
        $juegos = $repository->findByNoAutoma();

        return $this->json($juegos);
    }
}
