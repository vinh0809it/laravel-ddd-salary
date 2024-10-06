<?php

namespace Src\User\Application\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserDTO
{
    public function __construct(
        public ?string $id, 
        public string $name, 
        public string $email, 
        public ?string $password, 
        public bool $isAdmin, 
        public bool $isActive)
    {}

    public static function create(
        ?string $id, 
        string $name, 
        string $email, 
        ?string $password, 
        bool $isAdmin, 
        bool $isActive): self
    {
        return new self(
            id: $id, 
            name: $name, 
            email: $email, 
            password: $password, 
            isAdmin: $isAdmin, 
            isActive: $isActive
        );
    }

    public static function fromRequest(Request $request, ?int $id = null): self
    {
        return new self(
            id: $id,
            name: $request->string('name'),
            email: $request->string('email'),
            password: $request->string('password'),
            isAdmin: $request->boolean('is_admin', false),
            isActive: $request->boolean('is_active', true),
        );
    }

    public static function toRequest(mixed $entities): array
    {
        if(!($entities instanceof Collection)) {
            $entities = [$entities];
        }

        $result = [];

        foreach($entities as $entity) {
            $result[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'email' => $entity->email,
                'is_admin' => $entity->isAdmin
            ];
        };

        return $result;
    }

    public function getFullName(): string
    {
        return "{$this->name} {$this->email}";
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'is_admin' => $this->isAdmin,
            'is_active' => $this->isActive,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        return new self(
            id: $data['id'], 
            name: $data['name'], 
            email: $data['email'], 
            password: $data['password'], 
            isAdmin: $data['is_admin'], 
            isActive: $data['is_active']
        );
    }
}