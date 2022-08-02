<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\Request;

class DeleteTaskRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('delete', $this->entity);
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Task', $this->route('id'));

        parent::validateResolved();
    }
}
