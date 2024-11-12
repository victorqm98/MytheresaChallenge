<?php

declare(strict_types=1);

namespace MytheresaChallenge\Discount\Infrastructure\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use MytheresaChallenge\Discount\Domain\Discount;

class DiscountFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $discounts = [
            [
                'id' => '4c2d9a5e-1de6-4a32-acbd-6789ec0b80e1',
                'appliesTo' => '8c9d666c-96a3-4465-93be-10e67c5d6cd9',
                'type' => 'category',
                'percentage' => 30,
            ],
            [
                'id' => '3a48fbbe-cb72-4a3b-91f5-a09af1869d0a',
                'appliesTo' => '000003',
                'type' => 'sku',
                'percentage' => 15,
            ],
        ];

        foreach ($discounts as $discountData) {
            $discount = new Discount(
                id: $discountData['id'],
                type: $discountData['type'],
                appliesTo: $discountData['appliesTo'],
                percentage: $discountData['percentage']
            );

            $manager->persist($discount);
        }

        $manager->flush();
    }
}
