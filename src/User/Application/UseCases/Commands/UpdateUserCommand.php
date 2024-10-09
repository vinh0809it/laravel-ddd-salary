<?php
namespace Src\User\Application\UseCases\Commands;

use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Services\UserService;

class UpdateUserCommand
{
    public function __construct(
        private UserService $userService
    )
    {}

    public function execute(UserDTO $userDTO): void
    {
        $this->userService->updateUser($userDTO);
    }
}