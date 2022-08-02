<?php

namespace App\Services;

use App\Repositories\ScriptUserRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property ScriptUserRepository $repository
 */
class ScriptUserService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(ScriptUserRepository::class);
    }
}
