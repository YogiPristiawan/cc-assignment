<?php

namespace App\Exception\Http;

use Throwable;

class NotFoundException extends HttpException
{
    public function __construct(string $message, Throwable $previousException = null)
    {
        parent::__construct($message, 404, $previousException);
    }
}
