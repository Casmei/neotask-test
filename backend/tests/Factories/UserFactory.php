<?php

namespace Tests\Factories;

use App\Domain\Models\User;

class UserFactory
{
    public static function make(array $overrides = []): User
    {
        $name = $overrides["name"] ?? "Test User";
        $email = $overrides["email"] ?? "test@example.com";
        $password = $overrides["password"] ?? "senha123";

        $user = new User($name, $email, $password);

        if (isset($overrides["id"])) {
            $user->setId($overrides["id"]);
        }

        if (isset($overrides["is_admin"])) {
            $user->setIsAdmin($overrides["is_admin"]);
        }

        return $user;
    }

    public static function admin(array $overrides = []): User
    {
        return self::make(
            array_merge(
                [
                    "is_admin" => true,
                ],
                $overrides
            )
        );
    }
}
