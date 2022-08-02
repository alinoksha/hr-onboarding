<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tasks\AnswerTaskRequest;
use App\Http\Requests\Tasks\CreateTaskRequest;
use App\Http\Requests\Tasks\DeleteTaskRequest;
use App\Http\Requests\Tasks\SearchTasksRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function create(CreateTaskRequest $request, TaskService $taskService): JsonResponse
    {
        $data = $request->onlyValidated();
        $task = $taskService->create($data);

        return response()->json($task);
    }

    public function update(UpdateTaskRequest $request, TaskService $taskService, int $id): JsonResponse
    {
        $task = $taskService->update($id, $request->onlyValidated());

        return response()->json($task);
    }

    public function delete(DeleteTaskRequest $request, TaskService $taskService, int $id)
    {
        $taskService->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchTasksRequest $request, TaskService $taskService, int $scriptId): JsonResponse
    {
        $filter = $request->onlyValidated();
        $filter['script_id'] = $scriptId;

        if ($request->user()->company_id) {
            $filter['company_id'] = $request->user()->company_id;
        }

        return response()->json($taskService->search($filter));
    }

    public function answer(AnswerTaskRequest $request, TaskService $taskService, int $taskId): JsonResponse
    {
        $data = $request->onlyValidated();
        $data['task_id'] = $taskId;
        $data['user_id'] = request()->user()->id;

        $taskService->answer($data);

        return response()->json('', Response::HTTP_NO_CONTENT);
    }
}
