<?php
namespace App\Repository;

use App\Entity\Juego;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Juego>
 * @method Jugador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jugador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jugador[]    findAll()
 * @method Jugador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/

class JuegoRepository extends ServiceEntityRepository
{
     public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Juego::class);
    }

    public function findOneById($id): ?Juego
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByNombre($nombre): ?Juego
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.nombre = :val')
            ->setParameter('val', $nombre)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByAutoma(): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.dispAutoma = true')
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByNoAutoma(): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.dispAutoma = false')
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}