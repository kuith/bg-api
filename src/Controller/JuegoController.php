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
    #[Route('/', name: 'game_list', methods: ['GET'])]
    public function index(JuegoRepository $repository): Response
    {
        $juegos = $repository->findAll();

        //return $this->json($juegos);
        return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/id/{id<\d+>}', name: 'game_show', methods: ['GET'])]
    public function show(int $id, JuegoRepository $repository): Response
    {
        $juego = $repository->findGameById($id);

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado');
        }

        //return $this->json($juego);
         return $this->json($juego, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/nombre/{nombre}', name: 'game_findByName', methods: ['GET'])]
    public function findByName(String $nombre, JuegoRepository $repository): Response
    {
        $juego = $repository->findGameByName($nombre);

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado');
        }

        //return $this->json($juego);
         return $this->json($juego, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/anio/{anio}', name: 'game_findByYear', methods: ['GET'])]
    public function findByYear(String $anio, JuegoRepository $repository): Response
    {
        $juego = $repository->findGamesByAnio($anio);

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado.');
        }

        //return $this->json($juego);
         return $this->json($juego, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/editoriallocal/{editorialLocal}', name: 'game_findByLocalEditorial', methods: ['GET'])]
    public function findByLocalEditorial(String $editorialLocal, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGameByEditorialLocal($editorialLocal);

         if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos de la editorial local proporcionada: '.$editorialLocal
            );
        }

        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/editorialMadre/{editorialMadre}', name: 'game_findByOriginEditorial', methods: ['GET'])]
    public function findByOriginEditorial(String $editorialMadre, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGameByEditorialMadre($editorialMadre);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos de la editorial madre proporcionada: '.$editorialMadre
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/pricerange/{minPrice}/{maxPrice}', name: 'game_findByPriceRange', methods: ['GET'])]
    public function findByPriceRange(float $minPrice, float $maxPrice, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGamePriceRange($minPrice, $maxPrice);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos en ese rango de precios: ' .$minPrice .' - ' .$maxPrice
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/underprice/{price}', name: 'game_findBelowPrice', methods: ['GET'])]
    public function findBelowPrice(float $price, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGamesUnderPrice($price);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos por debajo de ese precio: ' .$price
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/search/overprice/{price}', name: 'game_findOverPrice', methods: ['GET'])]
    public function findOverPrice(float $price, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGamesOverPrice($price);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos por encima de ese precio: ' .$price
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/playersRange/{minPlayers}/{maxPlayers}', name: 'game_findByPlayesRange', methods: ['GET'])]
    public function findByPalersRange(int $minPlayers, int $maxPlayers, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGamesByPlayersRange($minPlayers, $maxPlayers);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos en ese rango de jugadores: ' .$minPlayers .' - ' .$maxPlayers
            );
        }
        //return $this->json($juegos);
        
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/minplayers/{minjugadores}', name: 'game_findByMinPlayes', methods: ['GET'])]
    public function findByMinPlayers(int $minjugadores, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGamesByMinPlayers($minjugadores);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos en ese mínimo de jugadores: ' .$minjugadores
            );
        }
        //return $this->json($juegos);
        
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/maxplayers/{maxJugadores}', name: 'game_findByMaxPlayers', methods: ['GET'])]
    public function findByMaxPlayers(int $maxJugadores, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGamesByMaxPlayers($maxJugadores);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos en ese máximo de jugadores: ' .$maxJugadores
            );
        }
        //return $this->json($juegos);
        
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }
    

    #[Route('/gamesbyautors/{id}', name: 'game_findByAuthors', methods: ['GET'])]    
    public function findByAuthors(int $id, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGamesByAuthor($id);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos de ese autor: ' .$id
            );
        }
        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }
    
    #[Route('/siautoma', name: 'game_findByYesAutoma', methods: ['GET'])]
    public function findYesAutoma(JuegoRepository $repository): Response
    {
        $juegos = $repository->findGameByAutoma();
         if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos con automa.'
            );
        }

        //return $this->json($juegos);
         return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/noautoma', name: 'game_findByNoAutoma', methods: ['GET'])]
    public function findNoAutoma(JuegoRepository $repository): Response
    {
        $juegos = $repository->findGameByNoAutoma();
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos sin automa.'
            );
        }
        //return $this->json($juegos);
        return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/tipo/{tipo}', name: 'game_findByType', methods: ['GET'])]
    public function findByType(String $tipo, JuegoRepository $repository): Response
    {
        $juegos = $repository->findGamesByType($tipo);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay juegos det tipo proporcionado: '.$tipo
            );
        }
        return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/expansionsByJuego/{idJuego}', name: 'game_findExpansionsByGame', methods: ['GET'])]
    public function findExpansionsByGame(String $idJuego, JuegoRepository $repository): Response
    {
        $juegos = $repository->findExpansionsByJuegoId($idJuego);
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay expansiones para el juego proporcionado: '.$idJuego
            );
        }
        return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/expansions', name: 'game_findAllExpansions', methods: ['GET'])]
    public function findAllExpansions(JuegoRepository $repository): Response
    {
        $juegos = $repository->findAllExpansions();
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay expansiones.'
            );
        }
        return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/expansionesWithAutoma', name: 'game_findExpansionsWithAutoma', methods: ['GET'])]
    public function findExpansionsWithAutoma(JuegoRepository $repository): Response
    {
        $juegos = $repository->findAllExpansionsWithConAutoma();
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay expansiones con automa.'
            );
        }
        return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/expansionesWithoutAutoma', name: 'game_findExpansionsWithoutAutoma', methods: ['GET'])]
    public function findExpansionsWithoutAutoma(JuegoRepository $repository): Response
    {
        $juegos = $repository->findAllExpansionsWithoutAutoma();
        if (!$juegos) {
            throw $this->createNotFoundException(
                'No hay expansiones sin automa.'
            );
        }
        return $this->json($juegos, Response::HTTP_OK, [], ['groups' => 'juego_lista']);
    }

    #[Route('/', name: 'game_create', methods: ['POST'])]
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
        $juego->setAnioPublicacion($data['aniopublicacion']);
        $juego->setDispAutoma($data['dispAutoma']);
        $juego->setEditorialLocal($data['editorialLocal']);
        $juego->setEditorialMadre($data['editorialMadre']);
        $juego->setPrecio($data['precio']);
        $juego->setMinJugadores($data['minJugadores']);
        $juego->setMaxJugadores($data['maxJugadores']);

        foreach ($data['autores'] as $autorId) {
            $autor = $em->getRepository(Autor::class)->findGameById($autorId);

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

    #[Route('/{id<\d+>}', name: 'game_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $juego = $entityManager->getRepository(Juego::class)->findGameById($id);

        if (!$juego) {
            throw $this->createNotFoundException(
                'Juego no encontrado: '.$id
            );
        }

        $entityManager->remove($juego);
        $entityManager->flush();
            
        return new Response('Juego eliminado!', 200);
    }

    
}
