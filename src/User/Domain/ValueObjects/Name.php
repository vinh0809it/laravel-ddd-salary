<?php
namespace Src\User\Domain\ValueObjects;

use Src\Common\Domain\Exceptions\ValueRequiredException;

final class Name
{
    private string $name;

    public function __construct(?string $name)
    {
        if (!$name) {
            throw new ValueRequiredException('name');
        }

        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}