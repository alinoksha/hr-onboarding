<?php

namespace App\Services;

use App\Repositories\SettingGroupRepository;
use Illuminate\Database\Eloquent\Collection;
use RonasIT\Support\Services\EntityService;

/**
 * @property SettingGroupRepository $repository
 */
class SettingGroupService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(SettingGroupRepository::class);
    }

    public function listWithSettings(?int $companyId): Collection
    {
        if (!$companyId) {
            return $this->with('settings')->get();
        }

        return $this->with(['settings' => function ($query) use ($companyId) {
            $query->where('settings.company_id', $companyId);
        }])->get();
    }
}
