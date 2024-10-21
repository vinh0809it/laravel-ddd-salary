<?php

namespace Src\Shared\Domain\Exceptions;

class InvalidDateRangeException extends DomainException 
{
    public function __construct()
    {
        $this->httpCode = 422;
        parent::__construct('FromDate must be before or equal to ToDate.');
    }
}