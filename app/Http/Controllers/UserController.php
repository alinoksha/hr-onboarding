<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\BlockUserRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\GetProfileRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\RestoreUserRequest;
use App\Http\Requests\User\SearchUsersRequest;
use App\Http\Requests\User\RestorePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserController extends Controller
{
    public function login(LoginRequest $request, UserService $userService): JsonResponse
    {
        $data = $request->validated();
        $token = $userService->login($data);

        if (empty($token)) {
            return response()->json([
                'message' => 'Error: unknown user name or bad password'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!Gate::allows('login')) {
            throw new AccessDeniedHttpException();
        }

        return response()->json([
            'token' => $token,
            'user' => $request->user()
        ]);
    }

    public function logout(): void
    {
        request()->user()->currentAccessToken()->delete();
    }

    public function search(SearchUsersRequest $request, UserService $userService): JsonResponse
    {
        $filters = $request->onlyValidated();
        $users = $userService->search($filters);

        return response()->json($users);
    }

    public function profile(GetProfileRequest $request, UserService $userService): JsonResponse
    {
        return response()->json($userService->profile($request->onlyValidated()));
    }

    public function create(CreateUserRequest $request, UserService $userService): JsonResponse
    {
        $data = $request->onlyValidated();
        $data['company_id'] = $request->user()->company_id ? $request->user()->company_id : $data['company_id'];

        $user = $userService->create($data);

        return response()->json($user);
    }

    public function resetPassword(ResetPasswordRequest $request, UserService $userService, int $userId)
    {
        $userService->resetPassword($userId);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(UpdateUserRequest $request, UserService $userService, int $id)
    {
        $userService->update($id, $request->onlyValidated());

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function updateProfile(UpdateProfileRequest $request, UserService $userService): JsonResponse
    {
        $profile = $userService->update(request()->user()->id, $request->onlyValidated());

        return response()->json($profile);
    }

    public function block(BlockUserRequest $request, UserService $userService, int $id)
    {
        $userService->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function restore(RestoreUserRequest $request, UserService $userService, int $id)
    {
        $userService->restore($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function forgotPassword(ForgotPasswordRequest $request, UserService $userService)
    {
        $userService->forgotPassword($request->input('email'));

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function restorePassword(RestorePasswordRequest $request, UserService $userService)
    {
        $userService->restorePassword([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        return response()->json('', Response::HTTP_NO_CONTENT);
    }
}
