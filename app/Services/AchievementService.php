<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use RonasIT\Support\Services\EntityService;
use App\Repositories\AchievementRepository;

/**
 * @mixin AchievementRepository
 * @property AchievementRepository $repository
 */
class AchievementService extends EntityService
{
    protected MediaService $mediaService;

    public function __construct()
    {
        $this->mediaService = app(MediaService::class);

        $this->setRepository(AchievementRepository::class);
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $response = $this
            ->searchQuery($filters)
            ->filterByList('script_id', 'scripts_ids');

        if (isset($filters['company_id'])) {
            $response->filterBy('script.company_id', 'company_id');
        }

        return $response->getSearchResults();
    }

    public function update($where, $data)
    {
        $originAchievement = $this->first($where);
        $updatedAchievement = $this->repository->update($where, $data);

        if ($updatedAchievement->wasChanged('incomplete_cover_id')) {
            $this->mediaService->delete($originAchievement->incomplete_cover_id);
        }

        if ($updatedAchievement->wasChanged('complete_cover_id')) {
            $this->mediaService->delete($originAchievement->complete_cover_id);
        }

        return $updatedAchievement;
    }
}
