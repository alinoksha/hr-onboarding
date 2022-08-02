<?php

namespace App\Http\Requests\Scripts;

use App\Http\Requests\Request;

class UpdateScriptRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->entity);
    }

    public function rules(): array
    {
        return [
            'title' => 'string|min:1|max:255|unique:scripts,title,' . $this->id,
            'description' => 'string|min:1',
            'cover_id' => 'exists:media,id'
        ];
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Script', $this->route('id'));

        parent::validateResolved();
    }
}
