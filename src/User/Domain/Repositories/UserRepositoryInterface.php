<?php

namespace Src\User\Domain\Repositories;

//use Src\Agenda\UserEloquentModel\Infrastructure\Repositories\UserDoesNotExistException;

use Illuminate\Support\Collection;
use Src\User\Domain\Model\User;
use Src\User\Domain\Model\ValueObjects\Password;

interface UserRepositoryInterface
{
    public function getUsers(?string $email, ?string $name): Collection;

    public function findUserById(string $userId): User;

    public function emailExists(string $email): bool;

    public function storeUser(User $user): User;

    public function updateUser(User $user): void;

    public function deleteUser(string $id): void;

}
