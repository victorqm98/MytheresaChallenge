<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Application\Service\Response;

use MytheresaChallenge\Product\Domain\Product;


final class ProductResponse
{
    public function __construct(
        private readonly Product $product,
        private readonly int $finalPrice,
        private readonly ?int $discountPercentage,
    ) {}

    public function toArray(): array
    {
        return [
            'sku' => $this->product->sku()->value(),
            'name' => $this->product->name()->value(),
            'category' => $this->product->categoryName()->value(),
            'price' => [
                'original' => $this->product->originalPrice()->value(),
                'final' => $this->finalPrice,
                'discountPercentage' => $this->discountPercentage !== null ? "{$this->discountPercentage}%" : null,
                'currency' => $this->product->currency()->value(),
            ],
        ];
    }
}


