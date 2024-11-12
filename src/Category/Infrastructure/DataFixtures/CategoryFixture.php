<?php

namespace MytheresaChallenge\Category\Infrastructure\DataFixtures;


use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use MytheresaChallenge\Category\Domain\Category;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            ['id' => '8c9d666c-96a3-4465-93be-10e67c5d6cd9', 'name' => 'boots'],
            ['id' => '4271ed62-35cc-4194-bfe8-81c114a9af1d', 'name' => 'sandals'],
            ['id' => 'ed337e15-7283-4d82-a106-774a708c2701', 'name' => 'sneakers'],
        ];

        foreach ($categories as $categoryData) {
            $category = new Category(
                id: $categoryData['id'],
                name: $categoryData['name']
            );

            $manager->persist($category);
            $this->addReference($categoryData['id'], $category);
        }

        $manager->flush();
    }
}
