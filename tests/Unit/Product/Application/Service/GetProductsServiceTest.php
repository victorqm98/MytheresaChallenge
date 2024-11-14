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

        $response = $this->getProductsService->execute([$category->id()->value()], [], 1, 5);

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

    public function testGetProductsWithoutDiscounts(): void
    {
        $category = new Category(Uuid::uuid(), 'boots');
        $product = new Product(
            Uuid::uuid(),
            'Test Product',
            'TEST001',
            $category,
            new Price(Uuid::uuid(), 10000, 'EUR')
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findAllByFilter')
            ->willReturn([$product]);

        $this->discountRepository
            ->expects($this->once())
            ->method('findAllByProduct')
            ->willReturn([]);

        $response = $this->getProductsService->execute([$category->id()->value()], [], 1, 5);

        $this->assertCount(1, $response->products());

        $productResponse = $response->products()[0]->toArray();
        $this->assertSame(10000, $productResponse['price']['original']);
        $this->assertSame(10000, $productResponse['price']['final']);
        $this->assertSame(null, $productResponse['price']['discountPercentage']);
    }

    public function testGetMultipleProductsWithDiscounts(): void
    {
        $category = new Category(Uuid::uuid(), 'boots');
        $product1 = new Product(
            Uuid::uuid(),
            'Test Product 1',
            'TEST001',
            $category,
            new Price(Uuid::uuid(), 10000, 'EUR')
        );
        $product2 = new Product(
            Uuid::uuid(),
            'Test Product 2',
            'TEST002',
            $category,
            new Price(Uuid::uuid(), 20000, 'EUR')
        );

        $discount_category = new Discount(Uuid::uuid(), 'category', $category->id()->value(), 5);
        $discount_sku_1 = new Discount(Uuid::uuid(), 'sku', 'TEST001', 15);
        $discount_sku_2 = new Discount(Uuid::uuid(), 'sku', 'TEST002', 10);

        $this->productRepository
            ->expects($this->once())
            ->method('findAllByFilter')
            ->willReturn([$product1, $product2]);

        $this->discountRepository
            ->expects($this->exactly(2))
            ->method('findAllByProduct')
            ->willReturnOnConsecutiveCalls(
                [$discount_category, $discount_sku_1],
                [$discount_category, $discount_sku_2]
            );

        $response = $this->getProductsService->execute([$category->id()->value()], [], 1, 5);

        $this->assertCount(2, $response->products());

        $productResponse1 = $response->products()[0]->toArray();
        $productResponse2 = $response->products()[1]->toArray();

        $this->assertSame(8500, $productResponse1['price']['final']);
        $this->assertSame(18000, $productResponse2['price']['final']);
    }

    public function testNoProductsFound(): void
    {
        $category = new Category(Uuid::uuid(), 'boots');

        $this->productRepository
            ->expects($this->once())
            ->method('findAllByFilter')
            ->willReturn([]);

        $this->discountRepository
            ->expects($this->never())
            ->method('findAllByProduct');

        $response = $this->getProductsService->execute([$category->id()->value()], [], 1, 5);

        $this->assertCount(0, $response->products());
    }

    public function testPagination(): void
    {
        $category = new Category(Uuid::uuid(), 'boots');
        $product1 = new Product(
            Uuid::uuid(),
            'Test Product 1',
            'TEST001',
            $category,
            new Price(Uuid::uuid(), 10000, 'EUR')
        );
        $product2 = new Product(
            Uuid::uuid(),
            'Test Product 2',
            'TEST002',
            $category,
            new Price(Uuid::uuid(), 20000, 'EUR')
        );

        $this->productRepository
            ->expects($this->exactly(2))
            ->method('findAllByFilter')
            ->willReturnOnConsecutiveCalls(
                [$product1],
                [$product2]
            );

        $this->discountRepository
            ->expects($this->exactly(2))
            ->method('findAllByProduct')
            ->willReturn([]);

        $response = $this->getProductsService->execute([$category->id()->value()], [], 1, 1);

        $this->assertCount(1, $response->products());

        $response = $this->getProductsService->execute([$category->id()->value()], [], 2, 1);

        $this->assertCount(1, $response->products());
    }

    public function testWithoutFilters(): void
    {
        $category = new Category(Uuid::uuid(), 'boots');
        $product = new Product(
            Uuid::uuid(),
            'Test Product',
            'TEST001',
            $category,
            new Price(Uuid::uuid(), 10000, 'EUR')
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findAllByFilter')
            ->willReturn([$product]);

        $this->discountRepository
            ->expects($this->once())
            ->method('findAllByProduct')
            ->willReturn([]);

        $response = $this->getProductsService->execute([], [], 1, 5);

        $this->assertCount(1, $response->products());
    }

    public function testInvalidFilters(): void
    {
        $category = new Category(Uuid::uuid(), 'boots');
        $product = new Product(
            Uuid::uuid(),
            'Test Product',
            'TEST001',
            $category,
            new Price(Uuid::uuid(), 10000, 'EUR')
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findAllByFilter')
            ->willReturn([]);

        $this->discountRepository
            ->expects($this->never())
            ->method('findAllByProduct')
            ->willReturn([]);

        $response = $this->getProductsService->execute([], ['TEST002'], 1, 5);

        $this->assertCount(0, $response->products());
    }
}

