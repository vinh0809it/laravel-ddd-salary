<?php

declare(strict_types=1);

namespace Src\User\Domain\Model\ValueObjects;

use Src\Common\Domain\Exceptions\IncorrectEmailFormatException;
use Src\Common\Domain\Exceptions\RequiredException;
use Src\Common\Domain\ValueObject;

final class Email extends ValueObject
{

    public function __construct(private string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new IncorrectEmailFormatException();
        }
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public function jsonSerialize(): string
    {
        return $this->email;
    }
}