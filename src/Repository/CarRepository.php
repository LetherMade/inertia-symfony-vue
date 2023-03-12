<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    /** @return array<int, Car> */
    public function findAllMatchingFilter(
        ?string $search,
        ?int $minPrice,
        ?int $maxPrice,
        int $limit,
        int $offset,
    ): array
    {
        $qb = $this->createQueryBuilderForFilter($search, $minPrice, $maxPrice);

        $qb
            ->addOrderBy('car.name')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $qb->getQuery()->execute();
    }

    public function countAllMatchingFilter(?string $search, ?int $minPrice, ?int $maxPrice): int
    {
        $qb = $this->createQueryBuilderForFilter($search, $minPrice, $maxPrice);

        return (int)$qb->select('COUNT(car.id)')->getQuery()->getSingleScalarResult();
    }

    protected function createQueryBuilderForFilter(?string $search, ?int $minPrice, ?int $maxPrice): QueryBuilder
    {
        $qb = $this->createQueryBuilder('car');

        if ($search !== null) {
            $qb
                ->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like('car.name', ':searchTerm'),
                    )
                )
                ->setParameter('searchTerm', '%' . $search . '%');
        }

        if ($minPrice !== null) {
            $qb
                ->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->gte('car.price', ':minPrice')
                    )
                )
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb
                ->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->lte('car.price', ':maxPrice')
                    )
                )
                ->setParameter('maxPrice', $maxPrice);
        }

        return $qb;
    }

    public function save(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function saveAndFlush(Car $entity): void
    {
        $this->save($entity, true);
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
