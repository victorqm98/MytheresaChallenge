<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Infrastructure\Product;

use MytheresaChallenge\Product\Domain\Product;
use MytheresaChallenge\Product\Domain\ProductRepository;
use MytheresaChallenge\Shared\Infrastructure\Mysql\DoctrineRepository;


final class MysqlProductRepository extends DoctrineRepository implements ProductRepository
{
    public function findAllByFilter(array $categoryIds, array $skus, int $page, int $limit): array
    {
        $filters = [];
        
        if (!empty($categoryIds)) {
            $filters['category'] = $categoryIds;
        }

        if (!empty($skus)) {
            $filters['sku'] = $skus;
        }

        $products = $this->searchWithFilters(Product::class, $filters, $page, $limit);
        
        return $products;
    }
}
