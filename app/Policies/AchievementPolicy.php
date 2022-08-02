<?php

namespace App\Policies;

use App\Models\Achievement;
use App\Models\Role;
use App\Models\User;

class AchievementPolicy extends BasePolicy
{
    public function get(User $user, Achievement $achievement): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || ($this->hasRole($user, Role::ADMIN, Role::MANAGER)
                && $achievement->company()->value('companies.id') === $user->company_id)
            || (in_array($achievement->script_id, $user->scripts()->allRelatedIds()->toArray())
                && $this->isEmployee($user->role_id));
    }

    public function delete(User $user, Achievement $achievement): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || ($this->hasRole($user, Role::ADMIN, Role::MANAGER)
                && $achievement->company()->value('companies.id') === $user->company_id);
    }

    public function update(User $user, Achievement $achievement): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || ($this->hasRole($user, Role::ADMIN, Role::MANAGER)
                && $achievement->company()->value('companies.id') === $user->company_id);
    }
}
