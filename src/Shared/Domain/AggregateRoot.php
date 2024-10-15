<?php

namespace Src\Shared\Domain;

use JsonSerializable;

abstract class AggregateRoot implements JsonSerializable
{
    abstract public function toArray(): array;

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}