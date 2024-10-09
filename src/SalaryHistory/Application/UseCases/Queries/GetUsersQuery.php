<?php

namespace Src\User\Application\UseCases\Queries;

use Illuminate\Support\Collection;
use Src\User\Domain\Repositories\IUserRepository;

class GetUsersQuery
{

    public function __construct(private IUserRepository $userRepository)
    {}

    public function handle(string $email = null, string $name = null): Collection
    {
        return $this->userRepository->getUsers($email, $name);
    }
}