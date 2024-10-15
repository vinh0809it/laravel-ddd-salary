<?php

namespace Src\User\Domain\Exceptions;

use Illuminate\Http\Response;
use Src\Shared\Domain\Exceptions\DomainException;

final class PasswordsDoNotMatchException extends DomainException
{
    public function __construct(string $message = 'Passwords do not match')
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}