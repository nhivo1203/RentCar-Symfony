<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository
{
    protected string $entityClass;

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        $this->entityClass = $entityClass;

        parent::__construct($registry, $entityClass);
    }

    public function add(AbstractEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AbstractEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    protected function sortBy(QueryBuilder $cars, string $orderBy): QueryBuilder
    {
        if (empty($orderBy)) {
            return $cars;
        }
        $orderBy = explode('.', $orderBy);
        $field = $orderBy[0];
        $order = $orderBy[1];
        switch ($field) {
            case 'created':
                $cars = $cars->orderBy($this->alias . ".createdAt", $order);
                break;

            case 'price':
                $cars = $cars->orderBy($this->alias . ".$field", $order);
                break;
            default:
                break;
        }
        return $cars;
    }


    protected function filter(QueryBuilder $cars, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $cars;
        }
        return $cars->where($this->alias . ".$field = :$field")->setParameter($field, $value);
    }

    protected function andFilter(QueryBuilder $cars, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $cars;
        }
        return $cars->andWhere($this->alias . ".$field = :$field")->setParameter($field, $value);
    }
}
