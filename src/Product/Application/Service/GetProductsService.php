<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Application\Service;

use MytheresaChallenge\Product\Domain\ProductRepository;
use MytheresaChallenge\Discount\Domain\DiscountRepository;
use MytheresaChallenge\Product\Application\Service\Response\ProductResponse;
use MytheresaChallenge\Product\Application\Service\Response\ProductsResponse;
use MytheresaChallenge\Product\Domain\Product;


final class GetProductsService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly DiscountRepository $discountRepository){}

    public function execute(array $categoryIds, array $skus, ?int $page, ?int $limit): ProductsResponse
    {
        /** @var Product[] $products */
        $products = $this->productRepository->findAllByFilter($categoryIds, $skus, $page, $limit);

        $productResponses = [];

        foreach ($products as $product) {
            $discounts = $this->discountRepository->findAllByProduct($product);

            $biggestDiscount = $product->getBiggestDiscount($discounts);
            $finalPrice = $product->getFinalPrice($biggestDiscount);

            $productResponses[] = new ProductResponse(
                $product,
                $finalPrice,
                $biggestDiscount,
            );
        }

        return new ProductsResponse($productResponses);
    }
}
