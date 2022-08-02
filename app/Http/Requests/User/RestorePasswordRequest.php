<?php

namespace App\Http\Requests\User;

use App\Services\UserService;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RestorePasswordRequest extends BaseUserRequest
{
    public function rules(): array
    {
       return [
           'email' => 'required|email|exists:users',
           'password' => 'required|string|confirmed|min:8',
       ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $this->checkToken();
    }

    public function checkToken()
    {
        $user = app(UserService::class)->first(['email' => $this->email]);

        $isExists = Password::tokenExists($user, $this->route('token'));

        if (!$isExists) {
            throw new UnprocessableEntityHttpException('Invalid token');
        }
    }
}
