<?php

namespace Src\Shared\Domain\Exceptions;

final class FactoryException extends DomainException
{
    public function __construct(string $message = 'Something is wrong with the factory.')
    {
        $this->httpCode = 422;
        parent::__construct($message);
    }
}