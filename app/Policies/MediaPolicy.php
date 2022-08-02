<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

class MediaPolicy extends BasePolicy
{
    public function delete(User $user, Media $media): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || $this->isOwner($media, $user);
    }

    public function get(User $user, Media $media): bool
    {
        return $this->isSuperAdmin($user->role_id)
            || $media->is_public
            || $media->company_id === $user->company_id;
    }
}
