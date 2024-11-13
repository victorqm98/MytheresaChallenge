<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Application\Service\Response;


final class ProductsResponse
{
    public function __construct(private readonly array $productResponses) {}

    public function toArray(): array
    {
        return array_map(fn(ProductResponse $response) => $response->toArray(), $this->productResponses);
    }

    public function products(): array
    {
        return $this->productResponses;
    }
}
