<?php
namespace Src\User\Domain\Factories;

use Src\Shared\Domain\Exceptions\FactoryException;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Domain\Entities\User;
use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Name;
use Src\User\Domain\ValueObjects\Password;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Throwable;

class UserFactory
{
    /**
     * @param string|null $id
     * @param string $name
     * @param string $email
     * @param string $password
     * @param bool $isAdmin
     * @param bool $isActive
     * 
     * @return User
     */
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
            throw new FactoryException('Error creating User: ' . $e->getMessage());
        }
    }

    public function fromDTO(UserDTO $dto): User
    {
        return $this->create(
            id: $dto->id,
            name: $dto->name,
            email: $dto->email,
            password: $dto->password,
            isAdmin: $dto->isAdmin,
            isActive: $dto->isActive,
        );
    }
    
    /**
     * @param UserEloquentModel $userEloquent
     * 
     * @return User
     */
    public function fromEloquent(UserEloquentModel $eloquent): User
    {
        return $this->create(
            id: $eloquent->id,
            name: $eloquent->name,
            email: $eloquent->email,
            password: $eloquent->password,
            isAdmin: $eloquent->is_admin,
            isActive: $eloquent->is_active,
        );
    }
}