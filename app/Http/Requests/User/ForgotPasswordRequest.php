<?php

namespace App\Http\Requests\User;

class ForgotPasswordRequest extends BaseUserRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users',
        ];
    }
}
