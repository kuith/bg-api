<?php

namespace App\Controller;
use App\Entity\Expansion;
use App\Repository\ExpansionRepository;
use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/expansiones')]

class ExpansionController extends AbstractController
{
    #[Route('/', name: 'app_expansiones_getAll', methods: ['GET'])]
    public function getAll(ExpansionRepository $repository): Response
    {
        $expansiones = $repository->findAll();
        return $this->json([
            'expansiones' => $expansiones
        ], 200, [], ['groups' => ['main']]);

    }

    #[Route('/search/{id<\d+>}', name: 'app_expansion_getById', methods: ['GET'])]
    public function getById(int $id, ExpansionRepository $repository): Response
    {
        $expansion = $repository->findOneById($id);

        if (!$expansion) {
            throw $this->createNotFoundException('Expansion no encontrada');
        }

        return $this->json($expansion);
    }

    #[Route('/', name: 'app_expansion_crearExpansion', methods: ['POST'])]
    public function crearExpansion (Request $request, JuegoRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $expansion = new Expansion();
        $expansion->setNombre($request->get('nombre'));

        $juego = $repository->findOneById($request->get('juego_id'));

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado');
        }

        $expansion->setJuego($juego);

        $entityManager->persist($expansion);
        $entityManager->flush();
        

        $data =  [
            'id' => $expansion->getId(),
            'nombre' => $expansion->getNombre(),
            'juego' => $expansion->getJuego()
        ];
            
        return $this->json($data, 200, ['groups' => ['main']]);
    }

    #[Route('/{id<\d+>}', name: 'expansion_edit', methods: ['PUT'])]
    public function update(Request $request, JuegoRepository $repository, EntityManagerInterface $entityManager, int $id): Response
    {
        $expansion = $entityManager->getRepository(Expansion::class)->findOneById($id);

        if (!$expansion) {
            throw $this->createNotFoundException(
                'Expansion no encontrado: '.$id
            );
        }

        $expansion->setNombre($request->get('nombre'));
        
        $juego = $repository->findOneById($request->get('juego_id'));

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado');
        }

        $expansion->setJuego($juego);

        $entityManager->persist($expansion);
        $entityManager->flush();
        

        $data =  [
            'id' => $expansion->getId(),
            'nombre' => $expansion->getNombre(),
            'juego' => $expansion->getJuego()
        ];
            
        return $this->json($data, 200, ['groups' => ['main']]);
    }
}