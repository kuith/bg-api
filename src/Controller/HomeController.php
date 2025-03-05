<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    #[Route('/', name: 'home', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        return new Response('Bienvenido a la API!');
    }
}
