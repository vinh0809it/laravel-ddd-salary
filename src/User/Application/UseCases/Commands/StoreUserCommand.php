<?php

namespace Src\User\Application\UseCases\Commands;

use Src\Shared\Domain\DomainEventDispatcher;
use Src\User\Domain\Entities\User;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Events\StoreUserEvent;
use Src\User\Domain\Services\UserService;

class StoreUserCommand
{
    public function __construct(
        private UserService $userService,
        private DomainEventDispatcher $domainEventDispatcher
    )
    {}

    public function execute(UserDTO $userDTO): User
    {
        $userEntity = $this->userService->storeUser($userDTO);
        $this->domainEventDispatcher->dispatch(new StoreUserEvent($userEntity->id));
        return $userEntity;
    }
}