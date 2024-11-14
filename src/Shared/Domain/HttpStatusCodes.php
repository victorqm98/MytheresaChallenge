<?php

declare(strict_types=1);

namespace MytheresaChallenge\Shared\Domain;

class HttpStatusCodes
{
    // Standard HTTP Status Codes
    public const OK = 200;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const INTERNAL_SERVER_ERROR = 500;
    public const SERVICE_UNAVAILABLE = 503;

    // Validation Errors
    public const INVALID_UUID = 1001;
}
