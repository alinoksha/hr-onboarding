<?php

namespace App\Repositories;

use App\Models\ScriptUser;
use RonasIT\Support\Repositories\BaseRepository;

class ScriptUserRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(ScriptUser::class);
    }
}
