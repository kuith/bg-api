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

    public function findMatchById($id): ?Partida
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findMatchByDate($fecha): array
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

    public function findMatchByPlayer($jugadorId): array //Juegos a los que ha jugado un determinado jugador
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

    public function findPlayersByGame ($juegoId): array // Jugadores que han jugado a un determinado juego
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.jugadores', 'jug')  // Relación con los jugadores
            ->innerJoin('p.juego', 'jueg')  // Relación con los juegos
            ->andWhere('jueg.id = :val')
            ->setParameter('val', $juegoId)
            ->orderBy('jug.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findWinnersByJuego($juegoId): array //ganadores a un determinado juego
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.ganadores', 'gan')  // Relación con los ganadores
            ->innerJoin('p.juego', 'jueg')  // Relación con los juegos
            ->andWhere('jueg.id = :val')
            ->setParameter('val', $juegoId)
            ->orderBy('gan.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getWinnersRanking(): array
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