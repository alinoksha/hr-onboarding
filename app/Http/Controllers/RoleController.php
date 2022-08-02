<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\SearchRolesRequest;
use App\Services\RoleService;

class RoleController extends Controller
{
    public function search(SearchRolesRequest $request,  RoleService $service)
    {
        $response = $service->search($request->validated());

        return response()->json($response);
    }
}
