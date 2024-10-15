<?php

namespace Src\SalaryHistory\Domain\Exceptions;

use Illuminate\Http\Response;
use Src\Shared\Domain\Exceptions\DomainException;

final class UnsupportedCurrencyException extends DomainException
{
    public function __construct(string $message = 'The provided currency is not supported.')
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}