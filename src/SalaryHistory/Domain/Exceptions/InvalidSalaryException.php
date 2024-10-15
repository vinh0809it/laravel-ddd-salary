<?php

namespace Src\SalaryHistory\Domain\Exceptions;

use Illuminate\Http\Response;
use Src\Shared\Domain\Exceptions\DomainException;

final class InvalidSalaryException extends DomainException
{
    public function __construct(string $message = 'The salary is not valid.')
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}