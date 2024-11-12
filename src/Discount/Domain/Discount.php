<?php

declare(strict_types=1);

namespace MytheresaChallenge\Discount\Domain;

use MytheresaChallenge\Shared\Domain\Aggregate\AggregateRoot;


class Discount extends AggregateRoot
{
    public function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly string $appliesTo,
        private readonly int $percentage,
    ) {}

    public function id(): Id
    {
        return new Id($this->id);
    }

    public function type(): Type
    {
        return new Type($this->type);
    }

    public function percentage(): Percentage
    {
        return new Percentage($this->percentage);
    }
}
