<?php

namespace App\Http\Requests\User;

class BlockUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('block', $this->entity);
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('User', $this->route('id'));

        parent::validateResolved();
    }
}
