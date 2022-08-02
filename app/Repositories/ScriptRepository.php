<?php

namespace App\Repositories;

use App\Models\Script;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property Script $model
 */
class ScriptRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Script::class);
    }
}
