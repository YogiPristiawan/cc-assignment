<?php

namespace App\Exception\Http;

use Exception;
use Throwable;

class HttpException extends Exception
{
    public $message, $statusCode;

    public function __construct(string $message, int $statusCode, Throwable $previousException = null)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;

        parent::__construct($message, $statusCode, $previousException);
    }
}
