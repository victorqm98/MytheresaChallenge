<?php

declare(strict_types=1);

namespace MytheresaChallenge\Discount\Domain;


use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;
use MytheresaChallenge\Shared\Domain\Aggregate\AggregateRoot;
final class Discount extends AggregateRoot
{
    public function __construct(
        private readonly string $id, 
        private readonly int $percentage,
        private readonly ArrayCollection|PersistentCollection $categories,
        private readonly ArrayCollection|PersistentCollection $skus
    ) {}

    public function id(): Id
    {
        return new Id($this->id);
    }

    public function percentage(): Percentage
    {
        return new Percentage($this->percentage);
    }

    public function categories(): ArrayCollection
    {
        return $this->categories;
    }

    public function skus(): ArrayCollection
    {
        return $this->skus;
    }
}
