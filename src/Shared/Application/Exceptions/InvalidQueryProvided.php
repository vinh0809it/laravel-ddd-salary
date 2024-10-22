<?php
namespace Src\Shared\Application\Exceptions;

use Src\Shared\Domain\Exceptions\DomainException;

final class InvalidQueryProvided extends DomainException
{
    public function __construct(string $message = 'Invalid query type provided.')
    {
        $this->httpCode = 422;
        parent::__construct($message);
    }
}