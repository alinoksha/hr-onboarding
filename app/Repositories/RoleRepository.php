<?php

namespace App\Repositories;

use App\Models\Role;
use RonasIT\Support\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Role::class);
    }
}
