<?php
namespace App\Repository;

use App\Entity\Jugador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class JugadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jugador::class);
    }

    // Agrega aquí tus métodos personalizados, como consultas avanzadas.
}