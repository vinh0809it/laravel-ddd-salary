<?php

namespace Src\SalaryHistory\Infrastructure\Services;

use Src\SalaryHistory\Domain\Services\External\IUserDomainService;
use Src\User\Domain\Services\UserService;

class UserDomainService implements IUserDomainService
{
    public function __construct(private UserService $userService) 
    {}

    public function userExists(string $userId): bool
    {
        return $this->userService->userExists($userId);
    }
}