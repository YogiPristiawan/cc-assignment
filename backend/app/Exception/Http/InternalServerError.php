<?php

namespace App\Exception\Http;

use Throwable;

class InternalServerError extends HttpException
{
    public function __construct(string $message, Throwable $previousException = null)
    {
        parent::__construct($message, 500, $previousException);
    }
}
