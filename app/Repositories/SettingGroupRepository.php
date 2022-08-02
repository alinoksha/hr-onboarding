<?php

namespace App\Repositories;

use App\Models\SettingGroup;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property SettingGroup $model
 */
class SettingGroupRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(SettingGroup::class);
    }
}
