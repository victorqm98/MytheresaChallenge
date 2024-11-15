<?php

declare(strict_types=1);

namespace MytheresaChallenge\Shared\Domain\Exception;

use Exception;
use Throwable;
use MytheresaChallenge\Shared\Domain\HttpStatusCodes;

class UuidInvalidException extends Exception
{
    private function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(string $id){
        return new self("Uuid ". $id ." is not valid", HttpStatusCodes::INVALID_UUID);
    }
}