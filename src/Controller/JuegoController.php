<?php

namespace App\Controller;
use App\Entity\Juego;
use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Util\Utils;

#[Route('/api/juegos')]

class JuegoController extends AbstractController
{
    #[Route('/', name: 'app_juegos_getAll', methods: ['GET'])]
    public function getAll(JuegoRepository $repository): Response
    {
        $juegos = $repository->findAll();

        $mySerialize = Utils::antiCircular();

        return $this->jSon(['expansiones'=>$mySerialize->serialize($juegos, 'json')]);
/*         return $this->json([
            'juegos' => $juegos
        ], 200, [], ['groups' => ['main']]);

       // return $this->json($juegos); */

    }

    #[Route('/search/{id<\d+>}', name: 'app_juego_getById', methods: ['GET'])]
    public function getById(int $id, JuegoRepository $repository): Response
    {
        $juego = $repository->findOneById($id);

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado');
        }

                $mySerialize = Utils::antiCircular();

        return $this->jSon(['expansiones'=>$mySerialize->serialize($juego, 'json')]);
    }

    #[Route('/', name: 'app_jugador_crearJuego', methods: ['POST'])]
    public function crearJuego (Request $request, EntityManagerInterface $entityManager): Response
    {
        $juego = new Juego();
        $juego->setNombre($request->get('nombre'));
        $juego->setUrimagen($request->get('urimagen'));
        $juego->setDimensionesCaja($request->get('dimensiones_caja'));
        $juego->setPrecio($request->get('precio'));
        $juego->setRangoJugadores($request->get('rango_jugadores'));
        $juego->setAutores($request->get('autores'));
        $juego->setEditorialMadre($request->get('editorail_madre'));
        $juego->setEditorialLocal($request->get('editorail_local'));

        if (!$juego->getNombre()) {
            return new JsonResponse(['error' => 'Missing required field: nombre'], 400);
        } 
         
        $entityManager->persist($juego);
        $entityManager->flush();

                $mySerialize = Utils::antiCircular();

        return $this->jSon(['expansiones'=>$mySerialize->serialize($juego, 'json')]);
    }

    #[Route('/{id<\d+>}', name: 'juego_edit', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $juego = $entityManager->getRepository(Juego::class)->findOneById($id);

        if (!$juego) {
            throw $this->createNotFoundException(
                'Juego no encontrado: '.$id
            );
        }

        $juego->setNombre($request->get('nombre'));
        $juego->setUrimagen($request->get('urimagen'));
        $juego->setDimensionesCaja($request->get('Dimensiones_caja'));
        $juego->setPrecio($request->get('precio'));
        $juego->setRangoJugadores($request->get('rango_jugadores'));
        $juego->setAutores($request->get('autores'));
        $juego->setEditorialMadre($request->get('editorail_madre'));
        $juego->setEditorialLocal($request->get('editorail_local'));

        $entityManager->flush();

            $mySerialize = Utils::antiCircular();

        return $this->jSon(['expansiones'=>$mySerialize->serialize($juego, 'json')]);
    }

    #[Route('/{id<\d+>}', name: 'juego_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $juego = $entityManager->getRepository(Juego::class)->findOneById($id);

        if (!$juego) {
            throw $this->createNotFoundException(
                'Juego no encontrado: '.$id
            );
        }

        $entityManager->remove($juego);
        $entityManager->flush();
            
        return new Response('Juego eliminado!', 200);
    }

    #[Route('/search/nombre/{nombre}', name: 'app_juego_getByNombre', methods: ['GET'])]
    public function getByNombre(String $nombre, JuegoRepository $repository): Response
    {
        $juego = $repository->findOneByNombre($nombre);

        if (!$juego) {
            throw $this->createNotFoundException('Jugador no encontrado');
        }

        $mySerialize = Utils::antiCircular();

        return $this->jSon(['expansiones'=>$mySerialize->serialize($juego, 'json')]);
        //return $this->json($juego);
    }
}
