<?php

namespace App\Controller;
use App\Entity\Juego;
use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/juegos')]

class JuegoController extends AbstractController
{
    #[Route('/', name: 'app_juegos_getAll', methods: ['GET'])]
    public function getAll(JuegoRepository $repository): Response
    {
        $juegos = $repository->findAll();

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);


        return $this->json($serializer->serialize($juegos, 'json'));
        //return $this->json($juegos);
    }

    #[Route('/search/{id<\d+>}', name: 'app_juego_getById', methods: ['GET'])]
    public function getById(int $id, JuegoRepository $repository): Response
    {
        $juego = $repository->findOneById($id);

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado');
        }

        return $this->json($juego);
    }

    #[Route('/', name: 'app_jugador_crearJuego', methods: ['POST'])]
    public function crearJuego (Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
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
    
        $data =  [
            'id' => $juego->getId(),
            'nombre' => $juego->getNombre(),
            'urimagen' => $juego->getUrimagen(),
            'dimensiones_caja' => $juego->getDimensionesCaja(),
            'precio' => $juego->getPrecio(),
            'rango_jugadores' => $juego->getRangoJugadores(),
            'autores' => $juego->getAutores(),
            'editorial_madre' => $juego->getEditorialMadre(),
            'editorial_local' => $juego->getEditorialLocal()
        ];
            
        return $this->json($data, 200);
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

        $data =  [
            'id' => $juego->getId(),
            'nombre' => $juego->getNombre(),
            'urimagen' => $juego->getUrimagen(),
            'dimensiones_caja' => $juego->getDimensionesCaja(),
            'precio' => $juego->getPrecio(),
            'rango_jugadores' => $juego->getRangoJugadores(),
            'autores' => $juego->getAutores(),
            'editorial_madre' => $juego->getEditorialMadre(),
            'editorial_local' => $juego->getEditorialLocal()
        ];
            
        return $this->json($data, 200);
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

        return $this->json($juego);
    }
}
