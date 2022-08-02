<?php

namespace App\Services;

use App\Repositories\AnswerRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property AnswerRepository $repository
 */
class AnswerService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(AnswerRepository::class);
    }
}
