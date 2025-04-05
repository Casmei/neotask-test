<?php
declare(strict_types=1);

namespace App\Domain\Repositories\Mappers;

use App\Domain\Models\User as DomainUser;
use App\Models\User as EloquentUser;

class UserMapper
{
    public static function toEloquent(DomainUser $user): EloquentUser
    {
        return EloquentUser::find($user->getId());
    }

    public static function toDomain(EloquentUser $eloquentUser): DomainUser
    {
        $domainUser = new DomainUser(
            $eloquentUser->name,
            $eloquentUser->email,
            $eloquentUser->password
        );

        $domainUser->setId($eloquentUser->id);
        $domainUser->setIsAdmin((bool) $eloquentUser->admin);

        return $domainUser;
    }
}
