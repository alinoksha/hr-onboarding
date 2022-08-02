<?php

namespace App\Repositories;

use App\Models\Answer;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property Answer $model
 */
class AnswerRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Answer::class);
    }
}
