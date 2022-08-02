<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use RonasIT\Support\Services\EntityService;

/**
 * @property TaskRepository $repository
 */
class TaskService extends EntityService
{
    protected AnswerService $answerService;

    public function __construct()
    {
        $this->answerService = app(AnswerService::class);

        $this->setRepository(TaskRepository::class);
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $response = $this
            ->searchQuery($filters)
            ->filterBy('script_id');

        if (isset($filters['company_id'])) {
            $response->filterBy('script.company_id', 'company_id');
        }

        return $response->getSearchResults();
    }

    public function answer(array $data): void
    {
        $this->answerService->updateOrCreate([
            'task_id' => $data['task_id'],
            'user_id' => $data['user_id']
        ], $data);
    }
}
