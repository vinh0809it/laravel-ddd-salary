<?php
namespace Src\Shared\Domain\Exceptions;

final class DatabaseException extends DomainException
{
    public function __construct(string $message = 'Something went wrong with the database.')
    {
        $this->httpCode = 503;
        parent::__construct($message);
    }
}