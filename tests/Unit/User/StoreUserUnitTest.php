<?php

namespace Tests\Unit\User;

use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\Exceptions\DatabaseException;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\Entities\User;
use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Name;
use Src\User\Domain\ValueObjects\Password;
use Src\User\Domain\Repositories\IUserRepository;
use Src\User\Domain\Services\UserService;
use Illuminate\Support\Str;

class StoreUserUnitTest extends TestCase
{
    use WithFaker;
    
    protected $userDTO;
    protected $userEntity;

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
        $this->setUpFaker();

        $name = $this->faker->name();
        $email = $this->faker->unique()->safeEmail();
        $password = Str::random(8);

        $this->userEntity = new User(
            id: null, 
            name: new Name($name),
            email: new Email($email),
            password: new Password($password),
            isAdmin: true,
            isActive: true
        );

        $this->userDTO = new UserDTO(
            id: null, 
            name: $name,
            email: $email,
            password: $password,
            isAdmin: true,
            isActive: true
        );

        // Mock dependencies
        $this->userFactory = Mockery::mock(UserFactory::class);
        $this->userRepository = Mockery::mock(IUserRepository::class);
    }

    public function test_storeUser_successful(): void
    {
        // Arrange
        $this->userFactory->shouldReceive('fromDTO')
            ->once()
            ->with($this->userDTO)
            ->andReturn($this->userEntity);

        $this->userRepository->shouldReceive('storeUser')
            ->once()
            ->with($this->userEntity)
            ->andReturn($this->userEntity);

        $userService = new UserService($this->userFactory, $this->userRepository);

        // Act
        $result = $userService->storeUser($this->userDTO);

        // Assertions
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($this->userDTO->name, (string)$result->name);
        $this->assertEquals($this->userDTO->email, (string)$result->email);
        $this->assertTrue($result->isAdmin);
        $this->assertTrue($result->isAdmin);
    }

    public function test_storeUser_failed(): void
    {
        // Arrange
        $this->userFactory->shouldReceive('fromDTO')
            ->once()
            ->with($this->userDTO)
            ->andReturn($this->userEntity);

        // Simulate an exception being thrown by the repository
        $this->userRepository->shouldReceive('storeUser')
        ->once()
        ->with($this->userEntity)
        ->andThrow(new DatabaseException('Failed to store user: Testing'));

        $userService = new UserService($this->userFactory, $this->userRepository);

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Failed to store user: Testing');

        // Act
        $userService->storeUser($this->userDTO);
    }
}
