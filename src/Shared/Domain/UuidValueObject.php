<?php

declare(strict_types=1);

namespace MytheresaChallenge\Shared\Domain;

use MytheresaChallenge\Shared\Domain\Exception\UuidInvalidException;
use Ramsey\Uuid\Uuid;

abstract class UuidValueObject
{
    public function __construct(protected string $value) {
        $this->ensureIsValidUuid($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function ensureIsValidUuid(string $id): void
    {
        $is_valid = Uuid::isValid($id);
        if (!$is_valid) {
            throw UuidInvalidException::create($id);
        }
    }

    public static function random(): string
    {
        return Uuid::uuid4()->toString();
    }
}
