<?php

namespace Src\User\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use Src\User\Domain\Factories\UserFactory;
use Src\Common\Infrastructure\BaseRepository;
use Src\User\Domain\Model\User;
use Src\User\Domain\Repositories\UserRepositoryInterface;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function getModel(): string
    {
        return UserEloquentModel::class;
    }

    public function __construct(private UserFactory $userFactory) 
    {
        parent::__construct();
    }

    public function getUsers(?string $email, ?string $name): Collection
    {
        $query = $this->model
            ->select(
                    'id',
                    'email',
                    'name',
                    'is_admin',
                    'is_active'
            );

        if($email) {
            $query->where('email', $email);
        }

        if($name) {
            $query->where('name', 'LIKE', "%$name%");
        }

        return $query->get()->map(function ($userEloquent) {
            return $this->userFactory->fromEloquent($userEloquent);
        });
    }

    public function findUserById(string $id): User
    {
        $userEloquent = $this->model->find($id);
        return $this->userFactory->fromEloquent($userEloquent);
    }

    public function emailExists(string $email): bool
    {
        return $this->model->where('email', $email)->exists();
    }

    public function storeUser(User $user): User
    {
        $userEloquent = $this->model->create($user->toArray());
        return $this->userFactory->fromEloquent($userEloquent);
    }

    public function updateUser(User $user): void
    {
        $this->model->find($user->id)->update($user->toArray());
    }

    public function deleteUser(string $id): void
    {
        $this->model->find($id)->delete();
    }
}