<?php

declare(strict_types=1);

namespace MytheresaChallenge\Price\Infrastructure\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use MytheresaChallenge\Price\Domain\Price;
use Doctrine\Bundle\FixturesBundle\Fixture;


class PriceFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $prices = [
            ['id' => 'd0904cfe-3084-461f-94b8-bf6eb5e867df', 'original' => 89000, 'currency' => 'EUR'],
            ['id' => '04455425-ee6a-422c-a640-456a01d68e20', 'original' => 99000, 'currency' => 'EUR'],
            ['id' => 'e0198742-2875-4151-b5fb-631af9402bee', 'original' => 71000, 'currency' => 'EUR'],
            ['id' => 'a95ac0b5-53d4-48c8-b065-89f96ce418e9', 'original' => 79500, 'currency' => 'EUR'],
            ['id' => 'c66853b3-7f26-4fd7-a791-484bab3c772b', 'original' => 59000, 'currency' => 'EUR'],
        ];

        foreach ($prices as $priceData) {
            $price = new Price(
                id: $priceData['id'],
                originalPrice: $priceData['original'],
                currency: $priceData['currency']
            );

            $manager->persist($price);
            $this->addReference($priceData['id'], $price);
        }

        $manager->flush();
    }
}
