<?php

declare(strict_types=1);

namespace MytheresaChallenge\Price\Domain;

use MytheresaChallenge\Shared\Domain\Aggregate\AggregateRoot;
use phpDocumentor\Reflection\Types\Null_;

class Price extends AggregateRoot
{
    public function __construct(
        private readonly string $id,
        private readonly int $originalPrice,
        private readonly string $currency
    ) {}

    public function id(): Id
    {
        return new Id($this->id);
    }

    public function originalPrice(): OriginalPrice
    {
        return new OriginalPrice($this->originalPrice);
    }

    public function currency(): Currency
    {
        return new Currency($this->currency);
    }

    public function getFinalPrice(?int $discount): int
    {
        if ($discount === 0 || $discount === Null) {
            return $this->originalPrice;
        }

        return (int) round($this->originalPrice * (1 - $discount / 100));
    }
}
