<?php

namespace Src\User\Application\Exceptions;

use Illuminate\Http\Response;
use Src\Shared\Domain\Exceptions\DomainException;

final class EmailAlreadyUsedException extends DomainException
{
    public function __construct()
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct('Email is already taken!');
    }
}