<?php

namespace Src\Common\Domain\Exceptions;

use Illuminate\Http\Response;

final class FactoryException extends DomainException
{
    public function __construct(string $message = 'Something is wrong with the factory.')
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}