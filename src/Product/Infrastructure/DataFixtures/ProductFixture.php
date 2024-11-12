<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Infrastructure\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use MytheresaChallenge\Product\Domain\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use MytheresaChallenge\Price\Infrastructure\DataFixtures\PriceFixture;
use MytheresaChallenge\Category\Infrastructure\DataFixtures\CategoryFixture;

class ProductFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'id' => '510d2e53-9e86-450a-9569-d5dd6e021a2a',
                'sku' => '000001',
                'name' => 'BV Lean leather ankle boots',
                'category' => '8c9d666c-96a3-4465-93be-10e67c5d6cd9',
                'price' => 'd0904cfe-3084-461f-94b8-bf6eb5e867df'
            ],
            [
                'id' => '56691ec1-d5da-446f-a748-fdfe7f3183ff',
                'sku' => '000002',
                'name' => 'BV Lean leather ankle boots',
                'category' => '8c9d666c-96a3-4465-93be-10e67c5d6cd9',
                'price' => '04455425-ee6a-422c-a640-456a01d68e20'
            ],
            [
                'id' => '7c3c24e6-ed1e-4b04-a74c-baa945ae0604',
                'sku' => '000003',
                'name' => 'Ashlington leather ankle boots',
                'category' => '8c9d666c-96a3-4465-93be-10e67c5d6cd9',
                'price' => 'e0198742-2875-4151-b5fb-631af9402bee'
            ],
            [
                'id' => 'd6239da9-14f1-48b4-8dd9-3d95083e8f0a',
                'sku' => '000004',
                'name' => 'Naima embellished suede sandals',
                'category' => '4271ed62-35cc-4194-bfe8-81c114a9af1d',
                'price' => 'a95ac0b5-53d4-48c8-b065-89f96ce418e9'
            ],
            [
                'id' => '45138faf-7270-44d3-97b8-e8c592fe9fbc',
                'sku' => '000005',
                'name' => 'Nathane leather sneakers',
                'category' => 'ed337e15-7283-4d82-a106-774a708c2701',
                'price' => 'c66853b3-7f26-4fd7-a791-484bab3c772b'
            ],
        ];

        foreach ($products as $productData) {
            $product = new Product(
                id: $productData['id'],
                sku: $productData['sku'],
                name: $productData['name'],
                category: $this->getReference($productData['category']),
                price: $this->getReference($productData['price'])
            );

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PriceFixture::class,
            CategoryFixture::class,
        ];
    }
}
