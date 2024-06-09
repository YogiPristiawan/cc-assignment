<?php

namespace App\Exception\Http;

use Throwable;

class BadRequestException extends HttpException
{
    public function __construct(string $message, Throwable $previousException = null)
    {
        parent::__construct($message, 400, $previousException);
    }
}
