<?php

namespace App\Http\Requests\Scripts;

use App\Http\Requests\Request;

class DeleteScriptRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('delete', $this->entity);
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Script', $this->route('id'));

        parent::validateResolved();
    }
}
