<?php

namespace App\Http\Requests\Scripts;

use App\Http\Requests\Request;

class SearchScriptsRequest extends Request
{
    public function rules(): array
    {
        return [
            'page' => 'integer',
            'per_page' => 'integer',
            'all' => 'integer',
            'order_by' => 'string',
            'desc' => 'boolean',
            'query' => 'string|nullable',
            'with' => 'array',
            'with.*' => 'in:achievement'
        ];
    }
}
