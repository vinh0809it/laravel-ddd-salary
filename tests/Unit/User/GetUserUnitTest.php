<?php

namespace Tests\Unit\User;

use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\Exceptions\DatabaseException;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Domain\Entities\User;
use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Name;
use Src\User\Domain\ValueObjects\Password;
use Src\User\Domain\Repositories\IUserRepository;
use Src\User\Domain\Services\UserService;

class GetUserUnitTest extends TestCase
{
    protected $userFactory;
    protected $userRepository;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->userFactory = Mockery::mock(UserFactory::class);
        $this->userRepository = Mockery::mock(IUserRepository::class);
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_GetUser_with_no_params(): void
    {
        $expectedUsers = new Collection([
            new User(1, new Name('User 1'), new Email('user1@example.com'), new Password('12345678'), false, true),
            new User(2, new Name('User 2'), new Email('user2@example.com'), new Password('12345678'), false, true),
        ]);

        $this->userRepository->shouldReceive('getUsers')
            ->with(null, null)
            ->andReturn($expectedUsers);

        $userService = new UserService($this->userFactory, $this->userRepository);

        $result = $userService->getUsers(null, null);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals($expectedUsers, $result);
    }

    public function test_GetUsers_with_email_param(): void
    {
        $email = 'test@example.com';
        $expectedUsers = new Collection([
            new User(1, new Name('User 1'), new Email('test@example.com'), new Password('12345678'), false, true),
        ]);

        $this->userRepository->shouldReceive('getUsers')
            ->with($email, null)
            ->andReturn($expectedUsers);

        $userService = new UserService($this->userFactory, $this->userRepository);

        $result = $userService->getUsers($email, null);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertEquals($email, $result->first()->getEmail());
    }

    public function test_GetUsers_throw_database_exception(): void
    {
        $this->userRepository->shouldReceive('getUsers')
            ->andThrow(new DatabaseException('Database error'));

        $userService = new UserService($this->userFactory, $this->userRepository);

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Failed to fetch users: Database error');

        $userService->getUsers(null, null);
    }

}
