<?php

namespace Src\User\Application\UseCases\Commands;

use Src\User\Domain\Model\User;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Events\StoreUserEvent;
use Src\User\Domain\Services\UserService;

class StoreUserCommand
{
    public function __construct(
        private UserService $userService,
    )
    {}

    public function execute(UserDTO $userDTO): User
    {
        $userEntity = $this->userService->storeUser($userDTO);
        event(new StoreUserEvent($userEntity));

        return $userEntity;
    }
}