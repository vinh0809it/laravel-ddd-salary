<?php

namespace Src\User\Domain\Events;

use Src\User\Domain\Model\User;

class StoreUserEvent
{
    public function __construct(private User $user)
    {
    }
}