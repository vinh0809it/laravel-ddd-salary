<?php

namespace Tests\Unit\User;

use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\Exceptions\DatabaseException;
use Src\Shared\Domain\Exceptions\EntityNotFoundException;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\Entities\User;
use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Name;
use Src\User\Domain\Repositories\IUserRepository;
use Src\User\Domain\Services\UserService;

class UpdateUserUnitTest extends TestCase
{
    protected $dto;
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

        $this->userFactory = Mockery::mock(UserFactory::class);
        $this->userRepository = Mockery::mock(IUserRepository::class);

        $this->dto = new UserDTO(
            id: 1, 
            name: 'Vinh Vo',
            email: 'vinh0809it@gmail.com',
            password: '12345678',
            isAdmin: true,
            isActive: true
        );
    }

    public function test_updateUser_successfully(): void
    {
        // Arrange
        $user = Mockery::mock(User::class);

        $this->userRepository->shouldReceive('findUserById')
            ->once()
            ->with($this->dto->id)
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
        $userService->updateUser($this->dto);

        // Assertions
        $this->assertTrue(true); // No exception means success
    }

    public function test_updateUser_when_user_is_not_existed(): void
    {
        // Arrange
        $this->userRepository->shouldReceive('findUserById')
            ->once()
            ->with($this->dto->id)
            ->andReturn(null);

        $userService = new UserService($this->userFactory, $this->userRepository);

        // Assert
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('User is not existed.');

        // Act
        $userService->updateUser($this->dto);
    }

    public function test_updateUser_failed(): void
    {
        // Arrange
        $this->userRepository->shouldReceive('findUserById')
            ->once()
            ->with($this->dto->id)
            ->andThrow(new DatabaseException('Database error'));

        $userService = new UserService($this->userFactory, $this->userRepository);

        // Assert
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Failed to get existing user: Database error');

        // Act
        $userService->updateUser($this->dto);
    }
}
