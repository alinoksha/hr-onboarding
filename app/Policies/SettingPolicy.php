<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;

class SettingPolicy extends BasePolicy
{
    public function update(User $user, ?Company $company): bool
    {
        return $user->company_id == $company->id
            && $this->isAdmin($user->role_id)
            || $this->isSuperAdmin($user->role_id);
    }

    public function list(User $user): bool
    {
        return $this->hasRole($user, Role::ADMIN, Role::SUPER_ADMIN);
    }
}
