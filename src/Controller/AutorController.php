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


#[Route('/api/authors')]

class AutorController extends AbstractController
{
    #[Route('/', name: 'author_list', methods: ['GET'])]
    public function index(AutorRepository $repository): Response
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

        //Verificar los campos requeridos
        $requiredFields = ['nombre', 'nacionalidad'];
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

        $autor = new Autor();
        $autor->setNombre($data['nombre']);
        $autor->setNacionalidad($data['nacionalidad']);

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

    #[Route('/{id}', name: 'author_update', methods: ['PATCH'])]
    public function actualizarJugador(int $id, Request $request, AutorRepository $autorRepository, EntityManagerInterface $em): JsonResponse
    {
        $autor = $autorRepository->find($id);

        if (!$autor) {
            return new JsonResponse(['error' => 'Autor no encontrado'], 404);
        }

        // Obtener datos del request
        $data = json_decode($request->getContent(), true);

        // Modificar solo si los valores existen en la petición

        if (isset($data['nombre'])) {
            $autor->setNombre($data['nombre']);
        }
        if (isset($data['nacionalidad'])) {
            $autor->setNacionalidad($data['nacionalidad']);
        }
        
        // Guardar cambios en la base de datos
        $em->flush();

        return new JsonResponse(['message' => 'Autor actualizado con éxito'], 200);
    }


    #[Route('/{id<\d+>}', name: 'author_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $autor = $entityManager->getRepository(Autor::class)->find($id);

        if (!$autor) {
            throw $this->createNotFoundException(
                'Autor no encontrado: '.$id
            );
        }

        $entityManager->remove($autor);
        $entityManager->flush();
            
        return new Response('Autor eliminado!', 200);
    }

    #[Route('/id/{id<\d+>}', name: 'author_show', methods: ['GET'])]
    public function show(int $id, AutorRepository $repository): Response
    {
        $autor = $repository->findAuthorById($id);

        if (!$autor) {
            throw $this->createNotFoundException('Autor no encontrado');
        }

        return $this->json($autor, Response::HTTP_OK, [], ['groups' => 'autor_detalle']);
    }

    #[Route('/name/{nombre}', name: 'author_findByname', methods: ['GET'])]
    public function findByName(String $nombre, AutorRepository $repository): Response
    {
        $autor = $repository->findAuthorByName($nombre);

        if (!$autor) {
            throw $this->createNotFoundException('Autor no encontrado');
        }

        return $this->json($autor, Response::HTTP_OK, [], ['groups' => 'autor_detalle']);
    }

    #[Route('/nationality/{nacionalidad}', name: 'author_findByNac', methods: ['GET'])]
    public function findByNac(String $nacionalidad, AutorRepository $repository): Response
    {
        $autor = $repository->findAuthorByNac($nacionalidad);

        if (!$autor) {
            throw $this->createNotFoundException('Autor no encontrado');
        }

        //return $this->json($juego);
        return $this->json($autor, Response::HTTP_OK, [], ['groups' => 'autor_detalle']);
    }
}