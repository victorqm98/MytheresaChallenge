<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Application\Service\Response;


final class ProductResponse
{
    public function __construct(
        private readonly string $sku,
        private readonly string $name,
        private readonly string $category,
        private readonly int $originalPrice,
        private readonly int $finalPrice,
        private readonly ?int $discountPercentage,
        private readonly string $currency
    ) {}

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category' => $this->category,
            'price' => [
                'original' => $this->originalPrice,
                'final' => $this->finalPrice,
                'discountPercentage' => "{$this->discountPercentage}%",
                'currency' => $this->currency,
            ],
        ];
    }
}


