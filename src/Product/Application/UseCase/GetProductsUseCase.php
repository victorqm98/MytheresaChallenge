<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Application\UseCase;

use MytheresaChallenge\Product\Application\Service\GetProductsService;
use MytheresaChallenge\Product\Application\Service\Response\ProductsResponse;

final class GetProductsUseCase
{
    public function __construct(private readonly GetProductsService $getProductsService){}

    public function execute(array $categoryIds, array $skus): ProductsResponse
    {
        return $this->getProductsService->execute($categoryIds, $skus);
    }
}
