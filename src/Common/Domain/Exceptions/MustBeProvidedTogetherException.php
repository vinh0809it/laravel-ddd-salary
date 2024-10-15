<?php

namespace Src\Common\Domain\Exceptions;

use Illuminate\Http\Response;

class MustBeProvidedTogetherException extends DomainException 
{
    public function __construct()
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct('Both from date and to date must be provided together.');
    }
}