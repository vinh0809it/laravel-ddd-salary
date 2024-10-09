<?php

namespace Src\Common\Domain\Exceptions;

use DomainException;

final class FactoryException extends DomainException
{
    public function __construct(string $message = 'Something is wrong with the factory.')
    {
        parent::__construct($message);
    }
}