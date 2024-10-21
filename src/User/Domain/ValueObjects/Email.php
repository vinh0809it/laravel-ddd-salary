<?php
namespace Src\User\Domain\ValueObjects;

use Src\Shared\Domain\Exceptions\IncorrectEmailFormatException;
use Src\Shared\Domain\ValueObject;

final class Email extends ValueObject
{
    public function __construct(private string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new IncorrectEmailFormatException();
        }
    }

    public function toString(): string
    {
        return $this->email;
    }
}