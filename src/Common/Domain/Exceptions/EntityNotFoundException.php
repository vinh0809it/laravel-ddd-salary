<?php

namespace Src\Common\Domain\Exceptions;

use Illuminate\Http\Response;

class EntityNotFoundException extends DomainException
{
    public function __construct(string $message = 'Entity not found.')
    {
        $this->httpCode = Response::HTTP_NOT_FOUND;
        parent::__construct($message);
    }
}