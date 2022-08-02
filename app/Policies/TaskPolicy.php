<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Services\ScriptUserService;

class TaskPolicy extends BasePolicy
{
    public function update(User $user, Task $task): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || ($this->hasRole($user, Role::ADMIN, Role::MANAGER)
                && $task->company()->value('companies.id') === $user->company_id);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || ($this->hasRole($user, Role::ADMIN, Role::MANAGER)
                && $task->company()->value('companies.id') === $user->company_id);
    }

    public function answer(User $user, Task $task): bool
    {
        return app(ScriptUserService::class)->exists(['script_id' => $task->script_id, 'user_id' => $user->id]);
    }
}
