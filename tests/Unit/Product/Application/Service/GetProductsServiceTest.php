<?php

declare(strict_types=1);

namespace MytheresaChallenge\Tests\Product\Application\Service;

use Faker\Provider\Uuid;
use PHPUnit\Framework\TestCase;
use MytheresaChallenge\Price\Domain\Price;
use MytheresaChallenge\Product\Domain\Product;
use MytheresaChallenge\Category\Domain\Category;
use MytheresaChallenge\Discount\Domain\Discount;
use MytheresaChallenge\Product\Domain\ProductRepository;
use MytheresaChallenge\Discount\Domain\DiscountRepository;
use MytheresaChallenge\Product\Application\Service\GetProductsService;
use MytheresaChallenge\Product\Application\Service\Response\ProductResponse;
use MytheresaChallenge\Product\Application\Service\Response\ProductsResponse;
use PHPUnit\Framework\MockObject\MockObject;

final class GetProductsServiceTest extends TestCase
{
    /** @var ProductRepository&MockObject */
    private ProductRepository|MockObject $productRepository;
    /** @var DiscountRepository&MockObject */
    private DiscountRepository|MockObject $discountRepository;
    private GetProductsService $getProductsService;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->discountRepository = $this->createMock(DiscountRepository::class);
        $this->getProductsService = new GetProductsService($this->productRepository, $this->discountRepository);
    }

    public function testGetProducts(): void
    {
        
        $category = new Category(Uuid::uuid(), 'boots');
        $product = new Product(
            Uuid::uuid(),
            'Test Product',
            'TEST001',
            $category,
            new Price(Uuid::uuid(), 10000, 'EUR')
        );

        $discount_category = new Discount(Uuid::uuid(), 'category', $category->id()->value(), 30);
        $discount_sku = new Discount(Uuid::uuid(), 'sku', 'TEST001', 15);
        
        $this->productRepository
            ->expects($this->once())
            ->method('findAllByFilter')
            ->willReturn([$product]);

        $this->discountRepository
            ->expects($this->once())
            ->method('findAllByProduct')
            ->willReturn([$discount_category, $discount_sku]);

        $response = $this->getProductsService->execute([$category->id()->value()], []);

        // Assert
        $this->assertInstanceOf(ProductsResponse::class, $response);
        $this->assertCount(1, $response->products());

        $productResponse = $response->products()[0];
        $this->assertInstanceOf(ProductResponse::class, $productResponse);
        $productResponse = $productResponse->toArray();
        $this->assertSame('TEST001', $productResponse['sku']);
        $this->assertSame('Test Product', $productResponse['name']);
        $this->assertSame(10000, $productResponse['price']['original']);
        $this->assertSame(7000, $productResponse['price']['final']);
        $this->assertSame('30%', $productResponse['price']['discountPercentage']);
        $this->assertSame('EUR', $productResponse['price']['currency']);
    }
}

