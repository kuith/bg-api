<?php

namespace App\Controller;
use App\Entity\Expansion;
use App\Repository\JuegoRepository;
use App\Repository\ExpansionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Util\Utils;


#[Route('/api/expansiones')]

class ExpansionController extends AbstractController
{
    #[Route('/', name: 'app_expansiones_getAll', methods: ['GET'])]
    public function getAll(ExpansionRepository $repository): Response
    {
        $expansiones = $repository->findAll();

        $mySerialize = Utils::antiCircular();

        return $this->jSon(['expansiones'=>$mySerialize->serialize($expansiones, 'json')]);

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

        $mySerialize = Utils::antiCircular();

        return $this->jSon(['expansiones'=>$mySerialize->serialize($expansion, 'json')]);
        //return $this->json($expansion);
    }

    #[Route('/', name: 'app_expansion_crearExpansion', methods: ['POST'])]
    public function crearExpansion (Request $request, JuegoRepository $repository, EntityManagerInterface $entityManager): Response
    {   
        $yaNombreExpansion = $entityManager->getRepository(Expansion::class)->findOneByNombre($request->get('nombre'));

        if ( $yaNombreExpansion){
            throw $this->createNotFoundException('Ya existe una expansión con ese nombre');
        }

        $expansion = new Expansion();
        $expansion->setNombre($request->get('nombre'));

        $juego = $repository->findOneById($request->get('juego_id'));

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado');
        }

        $expansion->setJuego($juego);

        $entityManager->persist($expansion);
        $entityManager->flush();

        $mySerialize = Utils::antiCircular();

        return $this->jSon(['expansion'=>$mySerialize->serialize($expansion, 'json')]);
        

       /*  $data =  [
            'id' => $expansion->getId(),
            'nombre' => $expansion->getNombre(),
            'juego' => $expansion->getJuego()
        ];
            
        return $this->jSon([$data]); */
    }

    #[Route('/{id<\d+>}', name: 'expansion_edit', methods: ['PUT'])]
    public function update(Request $request, JuegoRepository $repository, EntityManagerInterface $entityManager, int $id): Response
    {
        $expansion = $entityManager->getRepository(Expansion::class)->findOneById($id);
        $yaNombreExpansion = $entityManager->getRepository(Expansion::class)->findOneByNombre($request->get('nombre'));

        if (!$expansion) {
            throw $this->createNotFoundException(
                'Expansion no encontrada: '.$id
            );
        }

        if ( $yaNombreExpansion){
            throw $this->createNotFoundException('Ya existe una expansión con ese nombre');
        }

        $expansion->setNombre($request->get('nombre'));
        
        $juego = $repository->findOneById($request->get('juego_id'));

        if (!$juego) {
            throw $this->createNotFoundException('Juego no encontrado');
        }

        $expansion->setJuego($juego);

        $entityManager->persist($expansion);
        $entityManager->flush();

        $mySerialize = Utils::antiCircular();

        return $this->jSon(['expansion'=>$mySerialize->serialize($expansion, 'json')]);

        /* $data =  [
            'id' => $expansion->getId(),
            'nombre' => $expansion->getNombre(),
            'juego' => $expansion->getJuego(),
        ];
            
        return $this->jSon([$data]); */
    }
}