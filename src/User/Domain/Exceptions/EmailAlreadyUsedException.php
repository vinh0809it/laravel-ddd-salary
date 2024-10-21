<?php

namespace Src\User\Domain\Exceptions;

use Src\Shared\Domain\Exceptions\DomainException;

final class EmailAlreadyUsedException extends DomainException
{
    public function __construct()
    {
        $this->httpCode = 422;
        parent::__construct('Email is already taken!');
    }
}