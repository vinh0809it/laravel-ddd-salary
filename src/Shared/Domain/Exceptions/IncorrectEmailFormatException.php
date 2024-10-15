<?php

namespace Src\Shared\Domain\Exceptions;

use Illuminate\Http\Response;
use Src\Shared\Domain\Exceptions\DomainException;

final class IncorrectEmailFormatException extends DomainException
{
    public function __construct(string $message = 'Email is not valid.')
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}