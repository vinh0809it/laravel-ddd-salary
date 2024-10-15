<?php

namespace Src\User\Domain\Exceptions;

use Illuminate\Http\Response;
use Src\Shared\Domain\Exceptions\DomainException;

final class PasswordTooShortException extends DomainException
{
    public function __construct(string $message = 'The password needs to be at least 8 characters long')
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}