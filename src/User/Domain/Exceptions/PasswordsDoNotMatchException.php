<?php

namespace Src\User\Domain\Exceptions;

use Src\Shared\Domain\Exceptions\DomainException;

final class PasswordsDoNotMatchException extends DomainException
{
    public function __construct(string $message = 'Passwords do not match')
    {
        $this->httpCode = 422;
        parent::__construct($message);
    }
}