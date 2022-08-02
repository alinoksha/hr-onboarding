<?php

namespace App\Http\Controllers;

use App\Http\Requests\Scripts\CreateScriptRequest;
use App\Http\Requests\Scripts\UpdateScriptRequest;
use App\Http\Requests\Scripts\DeleteScriptRequest;
use App\Http\Requests\Scripts\GetScriptRequest;
use App\Http\Requests\Scripts\SearchScriptsRequest;
use App\Services\ScriptService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ScriptController extends Controller
{
    public function create(CreateScriptRequest $request, ScriptService $service): JsonResponse
    {
        $data = $request->onlyValidated();
        $data['company_id'] = $request->user()->company_id ? $request->user()->company_id : $data['company_id'];

        $result = $service->create($data);

        return response()->json($result);
    }

    public function get(GetScriptRequest $request, ScriptService $service, int $id): JsonResponse
    {
        $result = $service->find($id);

        return response()->json($result);
    }

    public function search(SearchScriptsRequest $request, ScriptService $service): JsonResponse
    {
        $result = $service->search($request->onlyValidated());

        return response()->json($result);
    }

    public function update(UpdateScriptRequest $request, ScriptService $service, int $id)
    {
        $service->update($id, $request->onlyValidated());

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteScriptRequest $request, ScriptService $service, int $id)
    {
        $service->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
