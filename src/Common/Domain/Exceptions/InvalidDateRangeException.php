<?php

namespace Src\Common\Domain\Exceptions;

use Illuminate\Http\Response;

class InvalidDateRangeException extends DomainException 
{
    public function __construct()
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct('From date must be before or equal to to date.');
    }
}