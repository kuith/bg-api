<?php

namespace App\Controller;

use App\Repository\JugadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}


/* #[Route('/product', name: 'create_product')]
public function createProduct(EntityManagerInterface $entityManager): Response
{
    $product = new Product();
    $product->setName('Keyboard');
    $product->setPrice(1999);
    $product->setDescription('Ergonomic and stylish!');

    // tell Doctrine you want to (eventually) save the Product (no queries yet)
    $entityManager->persist($product);

    // actually executes the queries (i.e. the INSERT query)
    $entityManager->flush();

    return new Response('Saved new product with id '.$product->getId());
} */