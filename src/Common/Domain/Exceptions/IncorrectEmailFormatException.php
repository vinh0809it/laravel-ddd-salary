<?php

namespace Src\Common\Domain\Exceptions;

use Illuminate\Http\Response;
use Src\Common\Domain\Exceptions\DomainException;

final class IncorrectEmailFormatException extends DomainException
{
    public function __construct(string $message = 'Email is not valid.')
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}