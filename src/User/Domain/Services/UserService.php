<?php

namespace Src\User\Domain\Services;

use Illuminate\Support\Collection;
use Src\Shared\Domain\Exceptions\DatabaseException;
use Src\Shared\Domain\Exceptions\EntityNotFoundException;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Entities\User;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Name;
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
        $user = $this->userFactory->fromDTO($userDTO);
        
        try {
            return $this->userRepository->storeUser($user);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to store user: ' . $e->getMessage());
        }
    }

    public function updateUser(UserDTO $userDTO): void
    {
        $existingUser = $this->getExistingUser($userDTO->id);

        if($userDTO->email) {
            $existingUser->changeEmail(new Email($userDTO->email));
        }

        if($userDTO->name) {
            $existingUser->changeName(new Name($userDTO->name));
        }

        try {
            $this->userRepository->updateUser($existingUser);
            
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to update user: ' . $e->getMessage());
        }
    }

    public function getExistingUser(string $userId): User
    {
        try {
            $existingUser = $this->userRepository->findUserById($userId);
        } catch(Throwable $e) {
            throw new DatabaseException('Failed to get existing user: '.$e->getMessage());
        }

        if(!$existingUser) {
            throw new EntityNotFoundException('User is not existed.');
        }

        return $existingUser;
    }
}