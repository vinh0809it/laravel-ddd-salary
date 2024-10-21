<?php

namespace Src\Shared\Domain\Exceptions;

use Src\Shared\Domain\Exceptions\DomainException;

final class IncorrectEmailFormatException extends DomainException
{
    public function __construct(string $message = 'Email is not valid.')
    {
        $this->httpCode = 422;
        parent::__construct($message);
    }
}