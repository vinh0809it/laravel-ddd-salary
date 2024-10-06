<?php

namespace Src\User\Application\UseCases\Commands;

use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\CommandInterface;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Model\ValueObjects\Name;

class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        private UserFactory $userFactory,
        private UserRepositoryInterface $userRepository
    )
    {}

    public function execute(UserDTO $userDTO): void
    {
        $existingUser = $this->userRepository->findUserById($userDTO->id);
        $existingUser->changeName(new Name($userDTO->name));
        $this->userRepository->updateUser($existingUser);
    }
}