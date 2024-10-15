<?php
namespace Src\Shared\Domain\Exceptions;

use Illuminate\Http\Response;

final class DatabaseException extends DomainException
{
    public function __construct(string $message = 'Something went wrong with the database.')
    {
        $this->httpCode = Response::HTTP_SERVICE_UNAVAILABLE;
        parent::__construct($message);
    }
}