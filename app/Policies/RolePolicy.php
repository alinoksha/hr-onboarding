<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy extends BasePolicy
{
    public function search(User $user): bool
    {
        return $this->hasRole($user, Role::ADMIN, Role::MANAGER, Role::SUPER_ADMIN);
    }
}
