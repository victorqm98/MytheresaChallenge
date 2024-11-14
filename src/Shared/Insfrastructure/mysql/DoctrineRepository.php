<?php

declare(strict_types=1);

namespace MytheresaChallenge\Shared\Infrastructure\Mysql;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use MytheresaChallenge\Shared\Domain\Aggregate\AggregateRoot;

abstract class DoctrineRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager){}

    protected function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function persist(AggregateRoot $entity): AggregateRoot
    {
        $this->entityManager()->persist($entity);
        $this->entityManager()->flush();

        return $entity;
    }

    protected function remove(AggregateRoot $entity): void
    {
        $this->entityManager()->remove($entity);
        $this->entityManager()->flush();
    }

    protected function find(string $className, string $id): ?AggregateRoot
    {
        return $this->entityManager()->find($className, $id);
    }

    protected function findBy(string $className, string $key, string $value): ?AggregateRoot
    {
        $repository = $this->entityManager->getRepository($className);
        return $repository->findOneBy([$key => $value]);
    }

    protected function findAll(string $className, array $ids, string $key): ArrayCollection
    {
        $repository = $this->entityManager->getRepository($className);

        return new ArrayCollection($repository->findBy([$key => $ids]));
    }

    public function searchWithFilters(string $entityClass, array $filters, int $page, int $limit): array
    {
        $qb = $this->entityManager()->createQueryBuilder();
        
        $qb->select('e')
           ->from($entityClass, 'e');
        
        foreach ($filters as $field => $values) {
            if (!empty($values)) {
                if (strpos($field, '.') !== false) {
                    list($relation, $fieldName) = explode('.', $field);
    
                    $qb->leftJoin('e.' . $relation, $relation)
                       ->addSelect($relation);
    
                    $qb->andWhere("$relation.$fieldName IN (:$field)")
                       ->setParameter($field, $values);
                } else {
                    $qb->andWhere("e.$field IN (:$field)")
                       ->setParameter($field, $values);
                }
            }
        }

        $offset = ($page - 1) * $limit;

        $qb->setFirstResult($offset)
           ->setMaxResults($limit);
    
        return $qb->getQuery()->getResult();
    }
}
