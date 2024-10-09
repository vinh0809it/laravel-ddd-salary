<?php
namespace Src\User\Domain\Model\ValueObjects;

use Src\Common\Domain\Exceptions\RequiredException;

final class Name
{
    private string $name;

    public function __construct(?string $name)
    {
        if (!$name) {
            throw new RequiredException('name');
        }

        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): string
    {
        return $this->name;
    }
}