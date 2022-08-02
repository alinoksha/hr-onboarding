<?php

namespace App\Traits;

use App\Models\Role;

trait AuthorizationTrait
{
    protected function hasRole($user, ...$roles): bool
    {
        return in_array($user->role_id, $roles);
    }

    protected function isAnyAdmin($roleId = null): bool
    {
        return $this->isAdmin($roleId) || $this->isSuperAdmin($roleId);
    }

    private function isRole($role, $roleId = null): bool
    {
        if (empty($roleId)) {
            $roleId = request()->user()->role_id;
        }

        return $roleId === $role;
    }

    protected function isSuperAdmin($roleId = null): bool
    {
        return $this->isRole(Role::SUPER_ADMIN, $roleId);
    }

    protected function isAdmin($roleId = null): bool
    {
        return $this->isRole(Role::ADMIN, $roleId);
    }

    protected function isManager($roleId =  null): bool
    {
        return $this->isRole(Role::MANAGER, $roleId);
    }

    protected function isEmployee($roleId =  null): bool
    {
        return $this->isRole(Role::EMPLOYEE, $roleId);
    }

    protected function isSelfAction($user): bool
    {
        return request()->user()->id === $user->id;
    }
}
