<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;

class CompanyPolicy extends BasePolicy
{
    public function update(User $user, Company $company): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || ($user->company_id === $company->id && $this->hasRole($user, Role::ADMIN));
    }
}
