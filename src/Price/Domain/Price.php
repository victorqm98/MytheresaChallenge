<?php

declare(strict_types=1);

namespace MytheresaChallenge\Price\Domain;


use MytheresaChallenge\Shared\Domain\Aggregate\AggregateRoot;

final class Price extends AggregateRoot
{
    public function __construct(
        private readonly string $id, 
        private readonly int $originalPrice,
        private readonly int $finalPrice,
        private readonly string $currency
    ) {}

    public function id(): Id
    {
        return new Id($this->id);
    }

    public function original(): OriginalPrice
    {
        return new OriginalPrice($this->originalPrice);
    }

    public function final(): FinalPrice
    {
        return new FinalPrice($this->finalPrice);
    }

    public function currency(): Currency
    {
        return new Currency($this->currency);
    }
}
