<?php

namespace Src\User\Domain\Events;

class StoreUserEvent
{
    public function __construct(public string $userId)
    {}
}