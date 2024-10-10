<?php

namespace App\Controller;
use App\Entity\Expansion;
use App\Entity\Juego;
use App\Repository\ExpansionRepository;
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

#[Route('/api/expansiones')]

class ExpansionController extends AbstractController
{
    #[Route('/', name: 'app_expansiones_getAll', methods: ['GET'])]
    public function getAll(ExpansionRepository $repository): Response
    {
        $expansiones = $repository->findAll();

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);

        return $this->jSon(['expansiones'=>$serializer->serialize($expansiones, 'json')]);

        /* return $this->json([
            'expansiones' => $expansiones
        ], 200, [], ['groups' => ['main']]); */

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
        $expansion->setJuego($juego);

        $entityManager->persist($expansion);
        $entityManager->flush();

        $data =  [
            'id' => $expansion->getId(),
            'nombre' => $expansion->getNombre(),
        ];
            
        return $this->jSon([$data]);
    }
}