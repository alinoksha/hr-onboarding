<?php

namespace App\Http\Controllers;

use App\Http\Requests\Media\CreateMediaRequest;
use App\Http\Requests\Media\DeleteMediaRequest;
use App\Http\Requests\Media\GetMediaContentRequest;
use App\Http\Requests\Media\GetMediaRequest;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    public function create(CreateMediaRequest $request, MediaService $service): JsonResponse
    {
        $file = $request->file('file');
        $data = $request->validated();
        $data['company_id'] = $request->user()->company_id ? $request->user()->company_id : $data['company_id'];

        $content = file_get_contents($file->getPathname());

        $media = $service->create($content, $file->getClientOriginalName(), $data);

        return response()->json($media);
    }

    public function delete(DeleteMediaRequest $request, MediaService $service, $id)
    {
        $service->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function getById(GetMediaRequest $request, MediaService $service, $id): JsonResponse
    {
        $result = $service->find($id);

        return response()->json($result);
    }

    public function getContentById(GetMediaContentRequest $request, MediaService $service, $id)
    {
        $result = $service->getContent($id);

        return redirect($result);
    }
}
