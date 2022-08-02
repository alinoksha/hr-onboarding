<?php

namespace App\Services;

use App\Repositories\ScriptProgressRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property ScriptProgressRepository $repository
 */
class ScriptProgressService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(ScriptProgressRepository::class);
    }
}
