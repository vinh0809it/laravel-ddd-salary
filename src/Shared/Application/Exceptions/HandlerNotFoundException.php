<?php
namespace Src\Shared\Application\Exceptions;

use Src\Shared\Domain\Exceptions\DomainException;

final class HandlerNotFoundException extends DomainException
{
    public function __construct(string $command)
    {
        $this->httpCode = 404;
        parent::__construct('No handler registered for '.$command);
    }
}