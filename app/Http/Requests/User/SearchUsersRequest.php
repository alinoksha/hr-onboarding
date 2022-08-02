<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class SearchUsersRequest extends Request
{
    public function rules(): array
    {
        return [
            'roles_ids' => 'array',
            'roles_ids.*' => 'int',
            'per_page' => 'int|min:1|max:100',
            'page' => 'int|min:1',
            'query' => 'nullable|string',
            'order_by' => 'string|in:' . $this->availableFields(),
            'desc' => 'boolean',
            'with' => 'array',
            'with.*' => 'string|in:' . $this->availableRelations(),
            'with_trashed' => 'boolean',
            'only_trashed' => 'boolean'
        ];
    }

    protected function availableFields(): string
    {
        $availableFields = [
            'id',
            'first_name',
            'last_name',
            'position',
            'created_at',
            'updated_at'
        ];

        return implode(',', $availableFields);
    }

    private function availableRelations(): string
    {
        $availableRelations = [
            'scripts',
            'onboarding_progress',
            'script_progress'
        ];

        return implode(',', $availableRelations);
    }
}
