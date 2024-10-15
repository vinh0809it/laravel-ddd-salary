<?php

namespace Tests\Unit\User;

use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\Exceptions\DatabaseException;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\Entities\User;
use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Name;
use Src\User\Domain\Repositories\IUserRepository;
use Src\User\Domain\Services\UserService;

class UpdateUserUnitTest extends TestCase
{
    protected $userFactory;
    protected $userRepository;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Mock dependencies
        $this->userFactory = Mockery::mock(UserFactory::class);

        $this->userRepository = Mockery::mock(IUserRepository::class);
    }

    public function test_updateUser_successfully(): void
    {
        // Arrange
        $user = Mockery::mock(User::class);

        $userDTO = new UserDTO(
            id: 1, 
            name: 'Vinh Vo',
            email: 'vinh0809it@gmail.com',
            password: '12345678',
            isAdmin: true,
            isActive: true
        );

        $this->userRepository->shouldReceive('findUserById')
            ->once()
            ->with($userDTO->id)
            ->andReturn($user);

        $user->shouldReceive('changeEmail')
            ->once()
            ->with(Mockery::type(Email::class));

        $user->shouldReceive('changeName')
            ->once()
            ->with(Mockery::type(Name::class));

        $this->userRepository->shouldReceive('updateUser')
            ->once()
            ->with($user);

        $userService = new UserService($this->userFactory, $this->userRepository);

        // Act
        $userService->updateUser($userDTO);

        // Assertions
        $this->assertTrue(true); // No exception means success
    }

    public function test_updateUser_failed(): void
    {
        // Arrange
        $userDTO = new UserDTO(
            id: 1, 
            name: 'Vinh Vo',
            email: 'vinh0809it@gmail.com',
            password: '12345678',
            isAdmin: true,
            isActive: true
        );

        $this->userRepository->shouldReceive('findUserById')
            ->once()
            ->with($userDTO->id)
            ->andThrow(new DatabaseException('Database error'));

        $userService = new UserService($this->userFactory, $this->userRepository);

        // Assert
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Failed to update user: Database error');

        // Act
        $userService->updateUser($userDTO);
    }
}
