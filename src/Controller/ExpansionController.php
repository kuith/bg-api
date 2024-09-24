<?php

namespace App\Controller;
use App\Entity\Expansion;
use App\Repository\ExpansionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/expansiones')]

class ExpansionController extends AbstractController
{
    #[Route('/', name: 'app_expansiones_getAll', methods: ['GET'])]
    public function getAll(ExpansionRepository $repository): Response
    {
        $expansiones = $repository->findAll();

        return $this->json($expansiones);
    }

    #[Route('/search/{id<\d+>}', name: 'app_Expansion_getById', methods: ['GET'])]
    public function getById(int $id, ExpansionRepository $repository): Response
    {
        $expansion = $repository->findOneById($id);

        if (!$expansion) {
            throw $this->createNotFoundException('Expansion no encontrada');
        }

        return $this->json($expansion);
    }

    #[Route('/', name: 'app_expansion_crearExpansion', methods: ['POST'])]
    public function crearExpansion (Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $juegoId = $request->get('juego_id');

        $expansion = new Expansion();
        $expansion->setNombre($request->get('nombre'));

        if (!$expansion->getNombre()) {
            return new JsonResponse(['error' => 'Missing required field: nombre'], 400);
        }
   
        $entityManager->persist($expansion);
        $entityManager->flush();
    
        $data =  [
            'id' => $expansion->getId(),
            'nombre' => $expansion->getNombre(),
        ];
            
        return $this->json($data, 200);
    }

    #[Route('/{id<\d+>}', name: 'expansion_edit', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $expansion = $entityManager->getRepository(Expansion::class)->findOneById($id);

        if (!$expansion) {
            throw $this->createNotFoundException(
                'Expansion no encontrada: '.$id
            );
        }

        $expansion->setNombre($request->get('nombre'));


        $entityManager->flush();

        $data =  [
            'id' => $expansion->getId(),
            'nombre' => $expansion->getNombre(),
        ];
            
        return $this->json($data, 200);
    }

    #[Route('/{id<\d+>}', name: 'expansion_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $expansion = $entityManager->getRepository(Expansion::class)->findOneById($id);

        if (!$expansion) {
            throw $this->createNotFoundException(
                'Expansion no encontrada: '.$id
            );
        }

        $entityManager->remove($expansion);
        $entityManager->flush();
            
        return new Response('Expansion eliminada!', 200);
    }

    #[Route('/search/nombre/{nombre}', name: 'app_expansion_getByNombre', methods: ['GET'])]
    public function getByNombre(String $nombre, ExpansionRepository $repository): Response
    {
        $expansion = $repository->findOneByNombre($nombre);

        if (!$expansion) {
            throw $this->createNotFoundException('Expansion no encontrada');
        }

        return $this->json($expansion);
    }
}
