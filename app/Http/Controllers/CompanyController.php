<?php

namespace App\Http\Controllers;

use App\Http\Requests\Companies\RegisterRequest;
use App\Http\Requests\Companies\UpdateCompanyRequest;
use App\Models\Role;
use App\Services\CompanyService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    public function register(RegisterRequest $request, CompanyService $companyService, UserService $userService)
    {
        $data = $request->onlyValidated();

        $data['user']['company_id'] = $companyService->create($data['company'])->id;
        $data['user']['role_id'] = Role::ADMIN;

        $userService->create($data['user']);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(UpdateCompanyRequest $request, CompanyService $companyService, int $id): JsonResponse
    {
        $company = $companyService->update($id, $request->onlyValidated());

        return response()->json($company);
    }
}
