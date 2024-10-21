<?php
namespace Src\User\Domain\ValueObjects;

use Src\Shared\Domain\Exceptions\ValueRequiredException;
use Src\Shared\Domain\ValueObject;

final class Name extends ValueObject
{
    private string $name;

    public function __construct(?string $name)
    {
        if (!$name) {
            throw new ValueRequiredException('name');
        }

        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }
}