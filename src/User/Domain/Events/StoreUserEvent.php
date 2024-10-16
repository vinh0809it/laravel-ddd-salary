<?php

namespace Src\User\Domain\Events;

use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Entities\User;

class StoreUserEvent
{
    public function __construct(public string $userId)
    {}
}