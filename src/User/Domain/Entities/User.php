<?php
namespace Src\User\Domain\Entities;

use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Name;
use Src\Shared\Domain\AggregateRoot;
use Src\User\Domain\ValueObjects\Password;

class User extends AggregateRoot
{
    public function __construct(
        public ?int $id,
        public Name $name,
        public Email $email,
        public ?Password $password = null,
        public bool $isAdmin = false,
        public bool $isActive = true
    ) {}

    // --- Business logic methods ---

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function changePassword(Password $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function changeEmail(Email $newEmail): void
    {
        $this->email = $newEmail;
    }

    public function changeName(Name $newName): void
    {
        $this->name = $newName;
    }

    // --- Getters ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()->toString(),
            'email' => (string)$this->getEmail()->toString(),
            'password' => (string)$this->getPassword()->toString(),
            'is_admin' => $this->isAdmin,
            'is_active' => $this->isActive,
        ];
    }
}
