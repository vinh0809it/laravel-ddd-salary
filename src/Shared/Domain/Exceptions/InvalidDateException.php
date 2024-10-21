<?php
namespace Src\Shared\Domain\Exceptions;

final class InvalidDateException extends DomainException
{
    public function __construct(string $message)
    {
        $this->httpCode = 422;
        parent::__construct($message);
    }
}