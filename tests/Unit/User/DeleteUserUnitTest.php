<?php

namespace Tests\Unit\User;

use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\Exceptions\DatabaseException;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\Repositories\IUserRepository;
use Src\User\Domain\Services\UserService;

class DeleteUserUnitTest extends TestCase
{
    protected $userFactory;
    protected $userRepository;

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock dependencies
        $this->userFactory = Mockery::mock(UserFactory::class);
        $this->userRepository = Mockery::mock(IUserRepository::class);
    }

    /**
     * @return void
     */
    public function test_deleteUser_successfully(): void
    {
        // Arrange
        $id = 1;

        $this->userRepository->shouldReceive('exists')
            ->once()
            ->with($id)
            ->andReturn(true);
            
        $this->userRepository->shouldReceive('deleteUser')
            ->once()
            ->with($id)
            ->andReturn();

        $userService = new UserService($this->userFactory, $this->userRepository);

        // Act
        $userService->deleteUser($id);

        // Assertion
        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function test_deleteUser_failed(): void
    {
        // Arrange
        $id = 1;

        $this->userRepository->shouldReceive('exists')
            ->once()
            ->with($id)
            ->andReturn(true);

        $this->userRepository->shouldReceive('deleteUser')
            ->once()
            ->with($id)
            ->andThrow(new DatabaseException('Database Error'));

        $userService = new UserService($this->userFactory, $this->userRepository);

        // Assertion
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database');

        // Act
        $userService->deleteUser($id);
    }
}
