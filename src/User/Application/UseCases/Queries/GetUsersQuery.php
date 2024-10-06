<?php

namespace Src\User\Application\UseCases\Queries;

use Illuminate\Support\Collection;
use Src\User\Domain\Policies\UserPolicy;
use Src\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class GetUsersQuery implements QueryInterface
{

    public function __construct(private UserRepositoryInterface $userRepository)
    {}

    public function handle(string $email = null, string $name = null): Collection
    {
        return $this->userRepository->getUsers($email, $name);
    }
}