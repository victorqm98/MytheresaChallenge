<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Domain;

interface ProductRepository
{
    public function findAllByFilter(array $categoryIds, array $skus, int $page, int $limit): array;
}
