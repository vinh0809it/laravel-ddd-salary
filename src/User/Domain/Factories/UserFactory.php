<?php
namespace Src\User\Domain\Factories;

use Src\Common\Domain\Exceptions\FactoryException;
use Src\User\Domain\Model\User;
use Src\User\Domain\Model\ValueObjects\Email;
use Src\User\Domain\Model\ValueObjects\Name;
use Src\User\Domain\Model\ValueObjects\Password;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Throwable;

class UserFactory
{
    public function create(?string $id, string $name, string $email, string $password, bool $isAdmin, bool $isActive): User
    {
        try {
            return new User(
                id: $id,
                name: new Name($name),
                email: new Email($email),
                password: new Password($password),
                isAdmin: $isAdmin,
                isActive: $isActive,
            );

        } catch(Throwable $e) {
            throw new FactoryException();
        }
    }

    public function fromEloquent(UserEloquentModel $userEloquent): User
    {
        try {
            return new User(
                id: $userEloquent->id,
                name: new Name($userEloquent->name),
                email: new Email($userEloquent->email),
                password: new Password($userEloquent->password),
                isAdmin: $userEloquent->is_admin,
                isActive: $userEloquent->is_active,
            );

        } catch(Throwable $e) {
            throw new FactoryException();
        }
    }
}