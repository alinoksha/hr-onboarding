<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\Request;

class SearchTasksRequest extends Request
{
    public function rules(): array
    {
        return [
            'per_page' => 'int|min:1|max:100',
            'page' => 'int|min:1',
            'query' => 'nullable|string',
            'order_by' => 'string|in:' . $this->availableFields(),
            'desc' => 'boolean',
        ];
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Script', $this->route('id'));

        parent::validateResolved();
    }

    private function availableFields(): string
    {
        $availableFields = [
            'id',
            'title',
            'response_type',
            'created_at',
            'updated_at'
        ];

        return implode(',', $availableFields);
    }
}
