<?php

namespace Src\Shared\Domain\Exceptions;

final class UnauthorizedUserException extends DomainException
{
    public function __construct(string $message = 'The user is not authorized to access this resource or perform this action')
    {
        $this->httpCode = 401;
        parent::__construct($message);
    }
}