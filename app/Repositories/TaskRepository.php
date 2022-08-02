<?php

namespace App\Repositories;

use App\Models\Task;
use RonasIT\Support\Repositories\BaseRepository;

class TaskRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Task::class);
    }
}
