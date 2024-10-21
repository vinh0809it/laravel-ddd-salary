<?php
namespace Src\Shared\Domain\Exceptions;

final class IncompleteDateRangeException extends DomainException
{
    public function __construct(string $message = 'From date and to date must be provided together.')
    {
        $this->httpCode = 422;
        parent::__construct($message);
    }
}