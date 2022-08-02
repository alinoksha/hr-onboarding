<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy extends BasePolicy
{
    public function create(User $user): bool
    {
        return $this->hasRole($user, Role::ADMIN, Role::MANAGER, Role::SUPER_ADMIN);
    }

    public function search(User $user): bool
    {
        return $this->hasRole($user, Role::ADMIN, Role::MANAGER, Role::SUPER_ADMIN);
    }

    public function resetPassword(User $user, User $updateUser): bool
    {
        return ($user->company_id == $updateUser->company_id)
            && ($this->hasRole($user, Role::ADMIN, Role::SUPER_ADMIN)
            || ($this->hasRole($user, Role::MANAGER) && $this->hasRole($updateUser, Role::EMPLOYEE)));
    }

    public function update(User $user, User $updateUser): bool
    {
        return (!$this->isSelfAction($updateUser))
            && ($user->company_id == $updateUser->company_id)
            && $this->hasRole($user, Role::ADMIN, Role::MANAGER, Role::SUPER_ADMIN);
    }

    public function block(User $user, User $blockUser): bool
    {
        return !($this->isSelfAction($blockUser))
            && ($user->company_id == $blockUser->company_id)
            && ($this->hasRole($user, Role::ADMIN, Role::SUPER_ADMIN)
                || ($this->hasRole($user, Role::MANAGER) && $this->hasRole($blockUser, Role::EMPLOYEE)));
    }

    public function restore(User $user, User $restoreUser): bool
    {
        return ($user->company_id == $restoreUser->company_id) && $this->hasRole($user, Role::ADMIN, Role::SUPER_ADMIN)
            || ($this->hasRole($user, Role::MANAGER) && $this->hasRole($restoreUser, Role::EMPLOYEE));
    }
}
