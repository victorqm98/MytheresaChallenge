<?php

declare(strict_types=1);

namespace MytheresaChallenge\Product\Domain;

class Category
{
    public function __construct(
        private readonly string $id, 
        private readonly string $name,
    ) {}

    public function id(): Id
    {
        return new Id($this->id);
    }

    public function name(): Name
    {
        return new Name($this->name);
    }
}
