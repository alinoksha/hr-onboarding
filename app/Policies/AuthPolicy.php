<?php

namespace App\Policies;

use App\Models\User;

class AuthPolicy extends BasePolicy
{
    public function login(User $user): bool
    {
        $header = config('defaults.app_name_header');

        if (request()->hasHeader($header)) {
            $roles = config('defaults.access.' . request()->header($header));

            return (empty($roles)) ? false : $this->hasRole($user, ...$roles);
        }

        return false;
    }
}
