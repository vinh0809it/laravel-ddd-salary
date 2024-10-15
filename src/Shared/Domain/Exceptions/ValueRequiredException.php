<?php
namespace Src\Shared\Domain\Exceptions;

use Illuminate\Http\Response;

final class ValueRequiredException extends DomainException
{
    public function __construct(string $message)
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}