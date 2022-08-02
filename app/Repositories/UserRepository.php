<?php

namespace App\Repositories;

use App\Models\User;
use RonasIT\Support\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(User::class);
    }
}
