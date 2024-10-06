<?php

declare(strict_types=1);

namespace Src\User\Domain\Model\ValueObjects;

use Src\User\Domain\Exceptions\PasswordTooShortException;
use Src\Common\Domain\ValueObject;

final class Password extends ValueObject
{
    public function __construct(private ?string $password = null)
    {
        if ($password) {
            if (strlen($password) < 8) {
                throw new PasswordTooShortException();
            }

            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
    }

    public static function fromString(string $password, string $confirmation): self
    {
        return new self($password, $confirmation);
    }

    public function getValue(): string
    {
        return $this->password;
    }

    public function __toString()
    {
        return $this->password ?? '';
    }
    
    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->password);
    }

    public function jsonSerialize(): string
    {
        return '';
    }
}