<?php

namespace App\Http\Requests\User;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UpdateUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->entity);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'string|min:2|max:255',
            'last_name' => 'string|min:2|max:255',
            'date_of_birth' => 'date_format:Y-m-d',
            'email' => 'email|unique:users,email,' . $this->id,
            'phone' => 'string|min:10|max:15|unique:users,phone,' . $this->id,
            'position' => 'string|max:255',
            'starts_on' => 'date_format:Y-m-d',
            'role_id' => 'int|exists:roles,id',
            'hr_id' => 'nullable|int|exists:users,id',
            'manager_id' => 'nullable|int|exists:users,id',
            'lead_id' => 'nullable|int|exists:users,id',
            'avatar_id' => 'exists:media,id',
            'scripts' => 'array',
            'scripts.*' => 'integer'
        ];
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('User', $this->route('id'));
        $this->checkRole();

        parent::validateResolved();

        $this->checkScriptsExists();
    }

    protected function checkRole(): void
    {
        if ($this->isSuperAdmin($this->entity->role_id)) {
            throw new AccessDeniedHttpException('Super Administrator can not be changed.');
        }

        if ($this->isRoleChanging() && $this->isAnyAdmin($this->entity->role_id)) {
            throw new AccessDeniedHttpException("Admin's role can not be changed.");
        }

        if ($this->isManager()) {
            if (!$this->isEmployee($this->entity->role_id)) {
                throw new AccessDeniedHttpException('Can be changed employee only.');
            }

            if ($this->isRoleChanging() && $this->isAnyAdmin($this->role_id)) {
                throw new AccessDeniedHttpException("Admin's role can not be got.");
            }
        }
    }

    protected function isRoleChanging()
    {
       return (!empty($this->role_id) && !$this->hasRole($this->entity, $this->role_id));
    }
}
