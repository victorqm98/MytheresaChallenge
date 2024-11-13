<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Domain;


use MytheresaChallenge\Price\Domain\Price;
use MytheresaChallenge\Category\Domain\Category;
use MytheresaChallenge\Category\Domain\Name;
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

    public function sku(): Sku
    {
        return new Sku($this->sku);
    }

    public function category(): Name
    {
        return $this->category->name();
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function currency(): string
    {
        return $this->price->currency()->value();
    }

    public function originalPrice(): int
    {
        return $this->price->originalPrice()->value();
    }

    public function getFinalPrice(int $discount): int
    {
        return $this->price->getFinalPrice($discount);
    }

    public function getBiggestDiscount(array $discounts): ?int
    {
        if(empty($discounts)){
            return null;
        }

        return max(array_map(fn($discount) => $discount->percentage()->value(), $discounts));
    }
}
