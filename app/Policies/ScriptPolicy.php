<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Script;
use App\Models\User;

class ScriptPolicy extends BasePolicy
{
    public function create(User $user): bool
    {
        return $this->hasRole($user, Role::ADMIN, Role::MANAGER, Role::SUPER_ADMIN);
    }

    public function get(User $user, Script $script): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || $user->company_id == $script->company_id;
    }

    public function delete(User $user, Script $script): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || $user->company_id == $script->company_id && $this->hasRole($user, Role::ADMIN, Role::MANAGER);
    }

    public function update(User $user, Script $script): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || $user->company_id == $script->company_id && $this->hasRole($user, Role::ADMIN, Role::MANAGER);
    }
}
