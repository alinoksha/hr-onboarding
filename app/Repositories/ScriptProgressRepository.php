<?php

namespace App\Repositories;

use App\Models\ScriptProgress;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property ScriptProgress $model
 */
class ScriptProgressRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(ScriptProgress::class);
    }
}
