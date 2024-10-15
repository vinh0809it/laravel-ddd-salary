<?php

namespace Tests\Unit\User;

use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Common\Domain\Exceptions\DatabaseException;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\Model\User;
use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Name;
use Src\User\Domain\ValueObjects\Password;
use Src\User\Domain\Repositories\IUserRepository;
use Src\User\Domain\Services\UserService;

class StoreUserUnitTest extends TestCase
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

    public function test_storeUser_successful(): void
    {
        // Arrange
        $userEntity = new User(
            id: null, 
            name: new Name('Vinh Vo'),
            email: new Email('vinh0809it@gmail.com'),
            password: new Password('12345678'),
            isAdmin: true,
            isActive: true
        );

        $userDTO = new UserDTO(
            id: null, 
            name: 'Vinh Vo',
            email: 'vinh0809it@gmail.com',
            password: '12345678',
            isAdmin: true,
            isActive: true
        );

        $this->userFactory->shouldReceive('create')
            ->once()
            ->andReturn($userEntity);

        $this->userRepository->shouldReceive('storeUser')
            ->once()
            ->with($userEntity)
            ->andReturn($userEntity);

        $userService = new UserService($this->userFactory, $this->userRepository);

        // Act
        $result = $userService->storeUser($userDTO);

        // Assertions
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('Vinh Vo', (string)$result->name);
        $this->assertEquals('vinh0809it@gmail.com', (string)$result->email);
        $this->assertTrue($result->isAdmin);
        $this->assertTrue($result->isAdmin);
    }

    public function test_storeUser_failed(): void
    {
        // Arrange
        $userEntity = new User(
            id: null, 
            name: new Name('Vinh Vo'),
            email: new Email('vinh0809it@gmail.com'),
            password: new Password('12345678'),
            isAdmin: true,
            isActive: true
        );

        $userDTO = new UserDTO(
            id: null, 
            name: 'Vinh Vo',
            email: 'vinh0809it@gmail.com',
            password: '12345678',
            isAdmin: true,
            isActive: true
        );

        $this->userFactory->shouldReceive('create')
            ->once()
            ->with(
                null,
                'Vinh Vo',
                'vinh0809it@gmail.com',
                '12345678',
                true,
                true
            )
            ->andReturn($userEntity);

        // Simulate an exception being thrown by the repository
        $this->userRepository->shouldReceive('storeUser')
        ->once()
        ->with($userEntity)
        ->andThrow(new DatabaseException('Failed to store user: Testing'));

        $userService = new UserService($this->userFactory, $this->userRepository);

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Failed to store user: Testing');

        // Act
        $userService->storeUser($userDTO);
    }
}
