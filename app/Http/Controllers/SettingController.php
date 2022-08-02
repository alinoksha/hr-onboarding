<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Services\SettingGroupService;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function list(SettingGroupService $settingGroupService): JsonResponse
    {
        $settings = $settingGroupService->listWithSettings(request()->user()->company_id);

        return response()->json($settings);
    }

    public function update(UpdateSettingsRequest $request, SettingService $settingService): JsonResponse
    {
        $settings = $settingService->update($request->onlyValidated());

        return response()->json($settings);
    }
}
