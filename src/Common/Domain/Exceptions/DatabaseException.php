<?php

namespace Src\Common\Domain\Exceptions;

use DomainException;

final class DatabaseException extends DomainException
{
    public function __construct(string $message = 'Something is wrong with the database.')
    {
        parent::__construct($message);
    }
}