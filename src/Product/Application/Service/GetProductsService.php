<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Application\Service;

use MytheresaChallenge\Product\Domain\ProductRepository;
use MytheresaChallenge\Discount\Domain\DiscountRepository;
use MytheresaChallenge\Product\Application\Service\Response\ProductResponse;
use MytheresaChallenge\Product\Application\Service\Response\ProductsResponse;

final class GetProductsService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly DiscountRepository $discountRepository){}

    public function execute(array $categoryIds, array $skus): ProductsResponse
    {
        $products = $this->productRepository->findAllByFilter($categoryIds, $skus);
        foreach ($products as $product) {
            $discounts = $this->discountRepository->findAllByProduct($product);

            $biggestDiscount = $product->getBiggestDiscount($discounts);
            $finalPrice = $product->getFinalPrice($biggestDiscount);

            $productResponses[] = new ProductResponse(
                $product->sku()->value(),
                $product->name()->value(),
                $product->category()->value(),
                $product->originalPrice(),
                $finalPrice,
                $biggestDiscount,
                $product->currency()
            );
        }
        return new ProductsResponse($productResponses);
    }
}
