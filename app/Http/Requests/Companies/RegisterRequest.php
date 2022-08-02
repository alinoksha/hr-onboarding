<?php

namespace App\Http\Requests\Companies;

use App\Http\Requests\Request;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            'company' => 'required|array',
            'company.name' => 'required|string|unique:companies,name',
            'user' => 'required|array',
            'user.first_name' => 'required|string|min:2|max:255',
            'user.last_name' => 'required|string|min:2|max:255',
            'user.date_of_birth' => 'required|date_format:Y-m-d',
            'user.email' => 'required|email|unique:users,email',
            'user.phone' => 'required|string|min:10|max:15|unique:users,phone',
            'user.position' => 'required|string|max:255'
        ];
    }
}
