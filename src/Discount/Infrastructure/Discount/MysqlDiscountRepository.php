<?php

declare(strict_types=1);

namespace MytheresaChallenge\Discount\Infrastructure\Discount;

use MytheresaChallenge\Product\Domain\Product;
use MytheresaChallenge\Discount\Domain\Discount;
use MytheresaChallenge\Discount\Domain\DiscountRepository;
use MytheresaChallenge\Shared\Infrastructure\Mysql\DoctrineRepository;


final class MysqlDiscountRepository extends DoctrineRepository implements DiscountRepository
{
    public function findAllByProduct(Product $product): array
    {
        $qb = $this->entityManager()->createQueryBuilder();
        
        $qb->select('d')
           ->from(Discount::class, 'd');
        
        if ($product->sku()->value()) {
            $qb->orWhere('d.appliesTo = :sku')
               ->setParameter('sku', $product->sku()->value());
        }
        
        if ($product->categoryId()->value()) {
            $qb->orWhere('d.appliesTo = :category_id')
               ->setParameter('category_id', $product->categoryId()->value());
        }

        return $qb->getQuery()->getResult();
    }
}


