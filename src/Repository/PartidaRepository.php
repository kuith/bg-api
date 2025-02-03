<?php
namespace App\Repository;

use App\Entity\Partida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partida>
 * @method Partida|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partida|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partida[]    findAll()
 * @method Partida[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/


class PartidaRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partida::class);
    }

    public function findOneById($id): ?Partida
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByFecha($fecha): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.fecha = :val')
            ->setParameter('val', $fecha)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByJugador($jugadorId): array
    {
        return $this->createQueryBuilder('j')
            ->innerJoin('j.jugadores', 'jug')  // Relación con los jugadores
            ->andWhere('jug.id = :val')
            ->setParameter('val', $jugadorId)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getRankingDeGanadores(): array
    {
        return $this->createQueryBuilder('p')
            ->select('jug.nombre, COUNT(p.id) as victorias')
            ->innerJoin('p.ganadores', 'jug')  // Relación con el jugador ganador
            ->groupBy('jug.id')
            ->orderBy('victorias', 'DESC')
            ->getQuery()
            ->getResult();
    }


}