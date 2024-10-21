<?php

namespace Src\User\Domain\Exceptions;

use Src\Shared\Domain\Exceptions\DomainException;

final class PasswordTooShortException extends DomainException
{
    public function __construct(string $message = 'The password needs to be at least 8 characters long')
    {
        $this->httpCode = 422;
        parent::__construct($message);
    }
}