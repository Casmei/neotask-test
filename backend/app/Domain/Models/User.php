<?php
declare(strict_types=1);

namespace App\Domain\Models;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private bool $isAdmin = false;

    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setIsAdmin(bool $isAdmin): User
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }
}
