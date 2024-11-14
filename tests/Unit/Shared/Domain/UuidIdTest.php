<?php

declare(strict_types=1);

namespace MytheresaChallenge\Tests\Product\Domain;

use PHPUnit\Framework\TestCase;
use MytheresaChallenge\Product\Domain\Id;
use MytheresaChallenge\Shared\Domain\Exception\UuidInvalidException;
use MytheresaChallenge\Shared\Domain\HttpStatusCodes;

final class UuidTest extends TestCase
{
    public function testThrowAnExceptionWhenUuidIsInvalid(): void
    {
        $this->expectException(UuidInvalidException::class);
        $this->expectExceptionMessage('Uuid 123-abc is not valid');
        $this->expectExceptionCode(HttpStatusCodes::INVALID_UUID);

        new Id('123-abc');
    }
}