<?php

declare(strict_types=1);

namespace MytheresaChallenge\Discount\Domain;

use MytheresaChallenge\Product\Domain\Product;

interface DiscountRepository
{
    public function findAllByProduct(Product $product): array;
}
