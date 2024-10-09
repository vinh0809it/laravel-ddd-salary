<?php
namespace Src\User\Application\UseCases\Queries;

use Illuminate\Support\Collection;
use Src\User\Domain\Services\UserService;

class GetUsersQuery
{
    public function __construct(private UserService $userService)
    {}

    public function handle(string $email = null, string $name = null): Collection
    {
        return $this->userService->getUsers($email, $name);
    }
}