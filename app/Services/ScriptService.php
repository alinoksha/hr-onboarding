<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use RonasIT\Support\Services\EntityService;
use App\Repositories\ScriptRepository;

class ScriptService extends EntityService
{
    protected MediaService $mediaService;

    public function __construct()
    {
        $this->mediaService = app(MediaService::class);

        $this->setRepository(ScriptRepository::class);
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $relations = (empty($filters['with']) ? null : $filters['with']);
        $filters['company_id'] = request()->user()->company_id;

        $scripts = $this->repository
            ->with($relations)
            ->searchQuery($filters)
            ->filterByQuery(['title']);

        if ($filters['company_id']) {
            $scripts = $scripts->filterBy('company_id');
        }

        return $scripts->getSearchResults();
    }

    public function update(int $where, array $data): Model
    {
        $originScript = $this->first($where);
        $updatedScript = $this->repository->update($where, $data);

        if ($updatedScript->wasChanged('cover_id')) {
            $this->mediaService->delete($originScript->cover_id);
        }

        return $updatedScript;
    }
}
