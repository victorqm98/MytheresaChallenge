<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Domain;


use MytheresaChallenge\Shared\Domain\Aggregate\AggregateRoot;

final class Product extends AggregateRoot
{
    public function __construct(
        private readonly string $id, 
        private readonly string $name,
        private readonly string $category
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
        return new Category($this->category);
    }
}
