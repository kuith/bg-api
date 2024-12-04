<?php
namespace App\Repository;

use App\Entity\Jugador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jugador>
 * @method Jugador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jugador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jugador[]    findAll()
 * @method Jugador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/

class JugadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jugador::class);
    }

    // Agrega aquí tus métodos personalizados, como consultas avanzadas.

    public function findOneById($id): ?Jugador
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByNombre($nombre): ?Jugador
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.nombre = :val')
            ->setParameter('val', $nombre)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByEmail($email): ?Jugador
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByRol($rol): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.rol = :val')
            ->setParameter('val', $rol)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPartidasGanadasPorJugador(int $jugadorId): array
    {
        return $this->createQueryBuilder('j')
            ->join('j.partidasGanadas', 'p')
            ->where('j.id = :jugadorId')
            ->setParameter('jugadorId', $jugadorId)
            ->getQuery()
            ->getResult();
    }


}