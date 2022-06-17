<?php

namespace App\Repository;

use App\Entity\Car;
use App\Request\CarRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends BaseRepository
{
    public const CAR_ALIAS = 'c';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }
    public function getAll(CarRequest $carRequest): array
    {

        $cars = $this->createQueryBuilder(static::CAR_ALIAS);
        $cars = $this->filter($cars, 'color', $carRequest->getColor());
        $cars = $this->andFilter($cars, 'brand', $carRequest->getBrand());
        $cars = $this->andFilter($cars, 'seats', $carRequest->getSeats());
        $cars = $this->sortBy($cars, $carRequest->getOrderBy());
        $cars->setMaxResults($carRequest->getLimit())->setFirstResult(0);

        return $cars->getQuery()->getResult();
    }
}
