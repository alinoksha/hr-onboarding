<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\User;

class ResetPasswordRequest extends Request
{
    private ?User $updateUser;

    public function authorize(): bool
    {
        return $this->user()->can('resetPassword', $this->updateUser);
    }

    public function validateResolved(): void
    {
        $updateUserId = $this->route('id');
        $this->checkEntityExists('User', $updateUserId);
        $this->updateUser = $this->entity;

        parent::validateResolved();
    }
}
