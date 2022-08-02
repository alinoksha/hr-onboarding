<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\Request;

class DeleteMediaRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('delete', $this->entity);
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Media', $this->route('id'));

        parent::validateResolved();
    }
}
