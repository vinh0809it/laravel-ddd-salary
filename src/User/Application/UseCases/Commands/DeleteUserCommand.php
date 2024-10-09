<?php
namespace Src\User\Application\UseCases\Commands;

use Src\User\Domain\Services\UserService;

class DeleteUserCommand
{
    public function __construct(
        private UserService $userService
    )
    {}

    public function execute(string $id): void
    {
        $this->userService->deleteUser($id);
    }
}