<?php

declare(strict_types=1);

namespace MytheresaChallenge\Category\Domain;

use MytheresaChallenge\Shared\Domain\Aggregate\AggregateRoot;

class Category extends AggregateRoot
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
