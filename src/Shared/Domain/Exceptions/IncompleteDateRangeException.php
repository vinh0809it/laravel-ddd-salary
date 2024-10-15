<?php
namespace Src\Shared\Domain\Exceptions;

use Illuminate\Http\Response;

final class IncompleteDateRangeException extends DomainException
{
    public function __construct(string $message = 'From date and to date must be provided together.')
    {
        $this->httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        parent::__construct($message);
    }
}