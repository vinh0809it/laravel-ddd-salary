<?php

namespace Src\User\Domain\Repositories;

use Illuminate\Support\Collection;
use Src\Shared\Domain\IBaseRepository;
use Src\User\Domain\Entities\User;

interface IUserRepository extends IBaseRepository
{
    public function getUsers(?string $email, ?string $name): Collection;

    public function findUserById(string $id): ?User;

    public function emailExists(string $email): bool;

    public function storeUser(User $user): User;

    public function updateUser(User $user): void;

    public function deleteUser(string $id): void;

}
