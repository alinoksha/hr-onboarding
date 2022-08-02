<?php

namespace App\Repositories;

use App\Models\Achievement;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property Achievement $model
 */
class AchievementRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Achievement::class);
    }
}
