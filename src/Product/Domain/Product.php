<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Domain;


use MytheresaChallenge\Price\Domain\Price;
use MytheresaChallenge\Category\Domain\Category;
use MytheresaChallenge\Shared\Domain\Aggregate\AggregateRoot;

class Product extends AggregateRoot
{
    public function __construct(
        private readonly string $id, 
        private readonly string $name,
        private readonly string $sku,
        private readonly Category $category,
        private readonly Price $price
    ) {}

    public function id(): Id
    {
        return new Id($this->id);
    }

    public function name(): Name
    {
        return new Name($this->name);
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function price(): Price
    {
        return $this->price;
    }
}
