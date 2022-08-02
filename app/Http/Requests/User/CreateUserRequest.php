<?php

namespace App\Http\Requests\User;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateUserRequest extends BaseUserRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|min:10|max:15|unique:users,phone',
            'position' => 'required|string|max:255',
            'starts_on' => 'required|date_format:Y-m-d',
            'role_id' => 'nullable|int|exists:roles,id',
            'hr_id' => 'nullable|int|exists:users,id',
            'manager_id' => 'nullable|int|exists:users,id',
            'lead_id' => 'nullable|int|exists:users,id',
            'avatar_id' => 'required|int|exists:media,id',
            'scripts' => 'array',
            'scripts.*' => 'int',
            'company_id' => 'int|exists:companies,id'
        ];
    }

    public function validateResolved(): void
    {
        $this->checkRole();
        $this->checkScriptsExists();

        if (!$this->user()->company_id && !$this->has('company_id')) {
            throw new UnprocessableEntityHttpException('Cannot create user without a company_id');
        }

        parent::validateResolved();
    }
}
