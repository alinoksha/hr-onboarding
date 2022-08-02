<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class RestoreUserRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('restore', $this->entity);
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('User', $this->route('id'), true);

        parent::validateResolved();
    }
}
