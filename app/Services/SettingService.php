<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use Illuminate\Database\Eloquent\Collection;
use RonasIT\Support\Services\EntityService;

/**
 * @property SettingRepository $repository
 */
class SettingService extends EntityService
{
    private SettingGroupService $settingGroupService;

    public function __construct(SettingGroupService $settingGroupService)
    {
        $this->setRepository(SettingRepository::class);
        $this->settingGroupService = $settingGroupService;
    }

    public function update(array $settings): Collection
    {
        foreach ($settings as $setting) {
            $this->repository->update($setting['id'], $setting);
        }

        return $this->settingGroupService->listWithSettings(request()->user()->company_id);
    }
}
