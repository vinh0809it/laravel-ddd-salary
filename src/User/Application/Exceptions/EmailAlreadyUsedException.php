<?php

namespace Src\User\Application\Exceptions;

use DomainException;

final class EmailAlreadyUsedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Email is already taken!');
    }
}