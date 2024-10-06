<?php

namespace Src\User\Application\UseCases\Commands;

use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\Model\User;
use Src\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\CommandInterface;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Events\StoreUserEvent;

class StoreUserCommand implements CommandInterface
{
    public function __construct(
        private UserFactory $userFactory,
        private UserRepositoryInterface $userRepository
    )
    {}

    public function execute(UserDTO $userDTO): User
    {
        $user = $this->userFactory->create(
            $userDTO->id,
            $userDTO->name,
            $userDTO->email,
            $userDTO->password,
            $userDTO->isAdmin,
            $userDTO->isActive,
        );
        
        $userEntity = $this->userRepository->storeUser($user);
        event(new StoreUserEvent($userEntity));

        return $userEntity;
    }
}