<?php

namespace Src\SalaryHistory\Domain\Exceptions;

use Illuminate\Http\Response;
use Src\Shared\Domain\Exceptions\DomainException;

final class UserHasSalaryRecordInYearException extends DomainException
{
    public function __construct(string $message = 'User already has a record for this year.')
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}