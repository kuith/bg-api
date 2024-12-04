<?php

namespace App\Controller;
use App\Entity\Autor;
use App\Repository\AutorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api/autores')]

class AutorController extends AbstractController
{
    #[Route('/', name: 'app_autor_getAll', methods: ['GET'])]
    public function getAll(AutorRepository $repository): Response
    {
        $autores = $repository->findAll();

        //return $this->json($autores);
        return $this->json($autores, Response::HTTP_OK, [], ['groups' => 'autor_detalle']);
    }

    #[Route('/', name: 'autor_create', methods: ['POST'])]
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
        if (!isset($data['nombre'])) {
            return new JsonResponse($data, 400);
        }

        $autor = new Autor();
        $autor->setNombre($data['nombre']);

        // Validar el objeto Autor
        $errors = $validator->validate($autor);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }
        
        // Guardar el jugador en la base de datos
        $em->persist($autor);
        $em->flush();

        return new JsonResponse(['message' => 'Autor creado con éxito'], 201);
    }

    #[Route('/{id<\d+>}', name: 'autor_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $autor = $entityManager->getRepository(Autor::class)->findOneById($id);

        if (!$autor) {
            throw $this->createNotFoundException(
                'Autor no encontrado: '.$id
            );
        }

        $entityManager->remove($autor);
        $entityManager->flush();
            
        return new Response('Autor eliminado!', 200);
    }

    #[Route('/search/{id<\d+>}', name: 'app_autor_getById', methods: ['GET'])]
    public function getById(int $id, AutorRepository $repository): Response
    {
        $autor = $repository->findOneById($id);

        if (!$autor) {
            throw $this->createNotFoundException('Autor no encontrado');
        }

        return $this->json($autor, Response::HTTP_OK, [], ['groups' => 'autor_detalle']);
    }

    #[Route('/search/nombre/{nombre}', name: 'app_autor_getByNombre', methods: ['GET'])]
    public function getByNombre(String $nombre, AutorRepository $repository): Response
    {
        $autor = $repository->findOneByNombre($nombre);

        if (!$autor) {
            throw $this->createNotFoundException('Autor no encontrado');
        }

        //return $this->json($juego);
        return $this->json($autor, Response::HTTP_OK, [], ['groups' => 'autor_detalle']);
    }
}