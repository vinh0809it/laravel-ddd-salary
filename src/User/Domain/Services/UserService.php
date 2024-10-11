<?php

namespace Src\User\Domain\Services;

use Illuminate\Support\Collection;
use Src\Common\Domain\Exceptions\DatabaseException;
use Src\Common\Domain\Exceptions\EntityNotFoundException;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\Model\ValueObjects\Email;
use Src\User\Domain\Model\ValueObjects\Name;
use Src\User\Domain\Repositories\IUserRepository;
use Throwable;

class UserService
{
    public function __construct(
        private UserFactory $userFactory,
        private IUserRepository $userRepository
    ) {}

    public function getUsers(string $email = null, string $name = null): Collection
    {
        try {
            return $this->userRepository->getUsers($email, $name);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to fetch users: ' . $e->getMessage());
        }
    }

    public function userExists(string $id): bool
    {
        return $this->userRepository->exists($id);
    }

    public function deleteUser(string $id): void
    {
        if(!$this->userRepository->exists($id)) {
            throw new EntityNotFoundException('User is not existed!');
        }

        try {

            $this->userRepository->deleteUser($id);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to store user: ' . $e->getMessage());
        }
    }

    public function storeUser(UserDTO $userDTO)
    {
        $user = $this->userFactory->create(
            $userDTO->id,
            $userDTO->name,
            $userDTO->email,
            $userDTO->password,
            $userDTO->isAdmin,
            $userDTO->isActive,
        );
        
        try {
            return $this->userRepository->storeUser($user);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to store user: ' . $e->getMessage());
        }
    }

    public function updateUser(UserDTO $userDTO): void
    {
        try {
            $existingUser = $this->userRepository->findUserById($userDTO->id);

            if($userDTO->email) {
                $existingUser->changeEmail(new Email($userDTO->email));
            }

            if($userDTO->name) {
                $existingUser->changeName(new Name($userDTO->name));
            }
            
            $this->userRepository->updateUser($existingUser);
            
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to update user: ' . $e->getMessage());
        }
    }
}