<?php
namespace Src\Common\Domain\Exceptions;

use Illuminate\Http\Response;

final class InvalidDateException extends DomainException
{
    public function __construct(string $message)
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}