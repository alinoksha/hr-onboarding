<?php

namespace App\Policies;

use App\Traits\AuthorizationTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization, AuthorizationTrait;

    protected function isOwner($entity, $user, $ownerField = 'user_id')
    {
        return $user->id === $entity->{$ownerField};
    }
}
