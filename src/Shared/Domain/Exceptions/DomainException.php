<?php

namespace Src\Shared\Domain\Exceptions;

use Exception;

class DomainException extends Exception
{
    protected $httpCode = 503;

    public function __construct(string $message = 'Domain Related Issue.')
    {
        parent::__construct($message);
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}