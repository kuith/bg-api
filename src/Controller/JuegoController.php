<?php

namespace App\Controller;
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


#[Route('/api/juegos')]

class JuegoController extends AbstractController
{
    #[Route('/', name: 'app_juego_getAll', methods: ['GET'])]
    public function getAll(JuegoRepository $repository): Response
    {
        $juegos = $repository->findAll();

        //return $this->json($juegos);
        return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/{id<\d+>}', name: 'app_juegos_getById', methods: ['GET'])]
    public function getById(int $id, JuegoRepository $repository): Response
    {
        $juego = $repository->findOneById($id);

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado');
        }

        //return $this->json($juego);
         return $this->json($juego, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/nombre/{nombre}', name: 'app_juego_getByNombre', methods: ['GET'])]
    public function getByNombre(String $nombre, JuegoRepository $repository): Response
    {
        $juego = $repository->findOneByNombre($nombre);

        if (!$juego) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        //return $this->json($juego);
         return $this->json($juego, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/editorialLocal/{editorialLocal}', name: 'app_juego_getByEditorialLocal', methods: ['GET'])]
    public function getByEditorialLocal(String $editorialLocal, JuegoRepository $repository): Response
    {
        $juegos = $repository->findByEditorialLocal($editorialLocal);

         if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos de la editorial local proporcionada: '.$editorialLocal
            );
        }

        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/editorialMadre/{editorialMadre}', name: 'app_juego_getByEditorialMadre', methods: ['GET'])]
    public function getByEditorialMadre(String $editorialMadre, JuegoRepository $repository): Response
    {
        $juegos = $repository->findByEditorialMadre($editorialMadre);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos de la editorial madre proporcionada: '.$editorialMadre
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/priceRange/{minPrice}/{maxPrice}', name: 'app_juego_getByPriceRange', methods: ['GET'])]
    public function getByPriceRange(float $minPrice, float $maxPrice, JuegoRepository $repository): Response
    {
        $juegos = $repository->findPriceRange($minPrice, $maxPrice);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos en ese rango de precios: ' .$minPrice .' - ' .$maxPrice
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/underPrice/{price}', name: 'app_juego_getByUnderPrice', methods: ['GET'])]
    public function getByUnderPrice(float $price, JuegoRepository $repository): Response
    {
        $juegos = $repository->findUnderPrice($price);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos por debajo de ese precio: ' .$price
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/overPrice/{price}', name: 'app_juego_getByUnderPrice', methods: ['GET'])]
    public function getByOverPrice(float $price, JuegoRepository $repository): Response
    {
        $juegos = $repository->findOverPrice($price);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos por encima de ese precio: ' .$price
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/playersRange/{minPlayers}/{maxPlayers}', name: 'app_juego_getByPlayersRange', methods: ['GET'])]
    public function getByPlayersRange(int $minPlayers, int $maxPlayers, JuegoRepository $repository): Response
    {
        $juegos = $repository->findPlayersRange($minPlayers, $maxPlayers);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos en ese rango de jugadores: ' .$minPlayers .' - ' .$maxPlayers
            );
        }
        //return $this->json($juegos);
        
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }
    

    #[Route('/search/gamesByAutors/{id}', name: 'app_juego_getGamesByAutor', methods: ['GET'])]    
    public function buscarJuegosPorAutores(int $id, JuegoRepository $repository): Response
    {
        $juegos = $repository->findByAuthor($id);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos de ese autor: ' .$id
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }
    
    #[Route('/search/siautoma', name: 'app_juego_getAllAutoma', methods: ['GET'])]
    public function getAllAutoma(JuegoRepository $repository): Response
    {
        $juegos = $repository->findByAutoma();

        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/noautoma', name: 'app_juego_getNoAllAutoma', methods: ['GET'])]
    public function getAllNoAutoma(JuegoRepository $repository): Response
    {
        $juegos = $repository->findByNoAutoma();

        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/', name: 'juego_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        JuegoRepository $juegoRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Intenta obtener los datos JSON
        try {
            $data = $request->toArray();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Formato de datos inválido'], 400);
        }

        // Validar que los campos requeridos están presentes
        if (!isset($data['nombre']) || !isset($data['baseExpansion']) || !isset($data['dispAutoma'])) {
            return new JsonResponse($data, 400);
        }

        $juego = new Juego();
        $juego->setNombre($data['nombre']);
        $juego->setBaseExpansion($data['baseExpansion']);

        // Si es una expansión, buscar el juego base
        if ($data['baseExpansion'] === 'expansion') {
            if (!isset($data['juegoBaseId'])) {
                return new JsonResponse(['error' => 'Debe proporcionar el juego base para una expansión'], 400);
            }

            $juegoBase = $juegoRepository->find($data['juegoBaseId']);
            if (!$juegoBase || $juegoBase->getBaseExpansion() !== 'base') {
                return new JsonResponse(['error' => 'El juego base no es válido'], 400);
            }

            $juego->setJuegoBase($juegoBase);
        }

        $juego->setTipo($data['tipo']);
        $juego->setDescripcion($data['descripcion']);
        $juego->setDispAutoma($data['dispAutoma']);
        $juego->setEditorialLocal($data['editorialLocal']);
        $juego->setEditorialMadre($data['editorialMadre']);
        $juego->setPrecio($data['precio']);
        $juego->setMinJugadores($data['minJugadores']);
        $juego->setMaxJugadores($data['maxJugadores']);

        foreach ($data['autores'] as $autorId) {
            $autor = $em->getRepository(Autor::class)->findOneById($autorId);

            if (!$autor) {
                return new JsonResponse(['error' => "Autor con ID $autorId no encontrado"], Response::HTTP_NOT_FOUND);
            }

            $juego->addAutor($autor);
        }

        // Validar el objeto Juego
        $errors = $validator->validate($juego);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse("['errors' => $errorMessages]", 400);
        }
        
        // Guardar el jugador en la base de datos
        $em->persist($juego);
        $em->flush();

        return new JsonResponse(['message' => 'Juego creado con éxito'], 201);
    }

    #[Route('/{id<\d+>}', name: 'juego_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $juego = $entityManager->getRepository(Juego::class)->findOneById($id);

        if (!$juego) {
            throw $this->createNotFoundException(
                'Jugador no encontrado: '.$id
            );
        }

        $entityManager->remove($juego);
        $entityManager->flush();
            
        return new Response('Juego eliminado!', 200);
    }

    
}
