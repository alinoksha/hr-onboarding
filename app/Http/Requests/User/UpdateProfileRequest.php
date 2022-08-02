<?php

namespace App\Http\Requests\User;

class UpdateProfileRequest extends BaseUserRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'string|min:2|max:255',
            'last_name' => 'string|min:2|max:255',
            'date_of_birth' => 'date_format:Y-m-d',
            'email' => 'email|unique:users,email,' . $this->user()->id,
            'phone' => 'string|min:10|max:15|unique:users,phone,' . $this->user()->id,
            'avatar_id' => 'exists:media,id'
        ];
    }
}
