<?php
namespace Src\Shared\Domain;

abstract class ValueObject
{
    abstract public function toString();

    public function equals(ValueObject $other): bool
    {
        return $this->toString() === $other->toString();
    }
}