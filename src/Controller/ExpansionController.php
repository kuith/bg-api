<?php

namespace App\Controller;
use App\Entity\Autor;
use App\Entity\Juego;
use App\Entity\Expansion;
use App\Repository\ExpansionRepository;
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
    #[Route('/', name: 'app_expansion_getAll', methods: ['GET'])]
    public function getAll(ExpansionRepository $repository): Response
    {
        $expansiones = $repository->findAll();

        return $this->json($expansiones, Response::HTTP_OK, [], ['groups' => 'expansion_lista']);
    }
    
    #[Route('/search/{id<\d+>}', name: 'app_expansiones_getById', methods: ['GET'])]
    public function getById(int $id, ExpansionRepository $repository): Response
    {
        $expansion = $repository->findOneById($id);

        if (!$expansion) {
            throw $this->createNotFoundException('Expansion no encontrado');
        }
        //return $this->json($juego);
        return $this->json($expansion, Response::HTTP_OK, [], ['groups' => 'expansion_lista']);
    }

    #[Route('/search/nombre/{nombre}', name: 'app_expansiones_getByNombre', methods: ['GET'])]
    public function getByNombre(String $nombre, ExpansionRepository $repository): Response
    {
        $expansion = $repository->findOneByNombre($nombre);

        if (!$expansion) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }
        //return $this->json($expansion);
         return $this->json($expansion, Response::HTTP_OK, [], ['groups' => 'expansion_lista']);
    }

    #[Route('/', name: 'expansion_create', methods: ['POST'])]
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
        if (!isset($data['nombre']) || !isset($data['descripcion']) || !isset($data['juego']) || !isset($data['autores']) || !isset($data['fecha_lanzamiento'])) {
            return new JsonResponse($data, 400);
        }

        $expansion = new Expansion();
        $expansion->setNombre($data['nombre']);
        $expansion->setDescripcion($data['descripcion']);
        $expansion->setFechaLanzamiento($data['fecha_lanzamiento']);

        foreach ($data['autores'] as $autorId) {
            $autor = $em->getRepository(Autor::class)->findOneById($autorId);

            if (!$autor) {
                return new JsonResponse(['error' => "Autor con ID $autorId no encontrado"], Response::HTTP_NOT_FOUND);
            }

            $expansion->addAutor($autor);
        }

        foreach ($data['juego'] as $juegoId) {
            $juego = $em->getRepository(Juego::class)->findOneById($juegoId);

            if (!$juego) {
                return new JsonResponse(['error' => "Juego con ID $juegoId no encontrado"], Response::HTTP_NOT_FOUND);
            }

            $expansion->setJuego($juego);
        }

        // Validar el objeto Expansion
        $errors = $validator->validate($expansion);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }
        
        // Guardar el jugador en la base de datos
        $em->persist($expansion);
        $em->flush();

        return new JsonResponse(['message' => 'Expansión creada con éxito'], 201);
        //return $this->json($expansion, Response::HTTP_OK, [], ['groups' => 'expansion_lista']);
    }

    #[Route('/{id<\d+>}', name: 'expansion_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $expansion = $entityManager->getRepository(Expansion::class)->findOneById($id);

        if (!$expansion) {
            throw $this->createNotFoundException(
                'Expansion no encontrado: '.$id
            );
        }

        $entityManager->remove($expansion);
        $entityManager->flush();
            
        return new Response('Expansión eliminada!', 200);
    }

}