<?php

namespace App\Controller;
use App\Entity\Expansion;
use App\Entity\Autor;
use App\Entity\Juego;
use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api/expansiones')]

class ExpansionController extends AbstractController
{
    #[Route('/', methods: ['POST'])]
    public function crearExpansion(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Obtener datos del cuerpo de la solicitud
        $data = json_decode($request->getContent(), true);

        // Validar los datos requeridos
        if (!isset($data['nombre'], $data['juego_base_id'])) {
            return new JsonResponse(['error' => 'Datos incompletos'], 400);
        }

        // Buscar el juego base por ID
        $juegoBase = $em->getRepository(Juego::class)->findOneById($data['juego_base_id']);
        if (!$juegoBase) {
            return new JsonResponse(['error' => 'Juego base no encontrado'], 404);
        }

        // Crear la expansión
        $expansion = new Expansion();
        $expansion->setNombre($data['nombre']);
        $expansion->setJuegoBase($juegoBase->getNombre());

        // Persistir y guardar
        $em->persist($expansion);
        $em->flush();

        return new JsonResponse(['message' => 'Expansión creada con éxito', 'id' => $expansion->getId()], 201);
    }
}