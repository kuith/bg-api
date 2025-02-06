<?php
namespace App\Repository;

use App\Entity\Juego;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Juego>
 * @method Juego|null find($id, $lockMode = null, $lockVersion = null)
 * @method Juego|null findOneBy(array $criteria, array $orderBy = null)
 * @method Juego[]    findAll()
 * @method Juego[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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

    public function findByEditorialLocal($editorialLocal): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.editorialLocal = :val')
            ->setParameter('val', $editorialLocal)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByEditorialMadre($editorialMadre): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.editorialMadre = :val')
            ->setParameter('val', $editorialMadre)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPriceRange(float $minPrice, float $maxPrice): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.precio >= :minPrice')
            ->andWhere('j.precio <= :maxPrice')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice)
            ->orderBy('j.precio', 'ASC')
            ->getQuery()
            ->getResult();
        ;
    }

    public function findUnderPrice(float $price): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.precio <= :price')
            ->setParameter('price', $price)
            ->orderBy('j.precio', 'ASC')
            ->getQuery()
            ->getResult();
        ;
    }

    public function findOverPrice(float $price): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.precio >= :price')
            ->setParameter('price', $price)
            ->orderBy('j.precio', 'ASC')
            ->getQuery()
            ->getResult();
        ;
    }

    public function findPlayersRange(float $minJugadores, float $maxJugadores): array
    {
        return $this->createQueryBuilder('j')
            ->Where('j.minJugadores >= :minJugadores')
            ->andWhere('j.maxJugadores<= :maxJugadores')
            ->setParameter('minJugadores', $minJugadores)
            ->setParameter('maxJugadores', $maxJugadores)
            ->getQuery()
            ->getResult();
        ;
    }

    public function findByMinPlayers(float $minJugadores): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.minJugadores >= :minJugadores')
            ->setParameter('minJugadores', $minJugadores)
            ->orderBy('j.minJugadores', 'ASC')
            ->getQuery()
            ->getResult();
        ;
    }

    public function findByMaxPlayers(float $maxJugadores): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.maxJugadores <= :maxJugadores')
            ->setParameter('maxJugadores', $maxJugadores)
            ->orderBy('j.maxJugadores', 'ASC')
            ->getQuery()
            ->getResult();
        ;
    }

    public function findByAuthor (int $autorId): array
    {
        return $this->createQueryBuilder('j')
            ->join('j.autores', 'a')
            ->andWhere('a.id = :autorId')
            ->setParameter('autorId', $autorId)
            ->getQuery()
            ->getResult();
    }

    public function findExpansionesByJuegoId (int $juegoId): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.juegoBase = :juegoId')  // Relación con el juego base
            ->andWhere('j.baseExpansion = :baseExpansion')  // Asegura que sea una expansión
            ->setParameter('juegoId', $juegoId)
            ->setParameter('baseExpansion', 'expansion')
            ->getQuery()
            ->getResult();
    }

    public function findAllExpansiones (): array
    {
        return $this->createQueryBuilder('j')
        ->andWhere('j.baseExpansion = :baseExpansion')  // Filtra solo expansiones
        ->setParameter('baseExpansion', 'expansion')
        ->getQuery()
        ->getResult();
    }

    public function findAllExpansionesConAutoma (): array
    {
        return $this->createQueryBuilder('j')
        ->andWhere('j.baseExpansion = :baseExpansion')  // Filtra solo expansiones
        ->andWhere('j.dispAutoma = :dispAutoma')
        ->setParameter('baseExpansion', 'expansion')
        ->setParameter('dispAutoma', true)
        ->getQuery()
        ->getResult();
    }

    public function findAllExpansionesSinAutoma (): array
    {
        return $this->createQueryBuilder('j')
        ->andWhere('j.baseExpansion = :baseExpansion')  // Filtra solo expansiones
        ->andWhere('j.dispAutoma = :dispAutoma')
        ->setParameter('baseExpansion', 'expansion')
        ->setParameter('dispAutoma', false)
        ->getQuery()
        ->getResult();
    }

    public function findByTipo($tipo): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.tipo = :val')
            ->setParameter('val', $tipo)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByAnio($anioPublicacion): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.anioPublicacion = :val')
            ->setParameter('val', $anioPublicacion)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

        public function findJuegosPorJugador(int $jugadorId): array
    {
        return $this->createQueryBuilder('jugador')
            ->select('DISTINCT j') // Se selecciona la entidad Juego
            ->join('jugador.partidas', 'p') // Jugador tiene relación con Partida
            ->join('p.juego', 'j') // Partida tiene relación con Juego
            ->where('jugador.id = :jugadorId')
            ->setParameter('jugadorId', $jugadorId)
            ->getQuery()
            ->getResult();
    }
}