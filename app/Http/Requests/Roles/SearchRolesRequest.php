<?php

namespace App\Http\Requests\Roles;

use App\Http\Requests\Request;

class SearchRolesRequest extends Request
{
    public function rules(): array
    {
        return [
            'all' => 'integer',
            'order_by' => 'string|in:id,name',
            'page' => 'integer',
            'per_page' => 'integer',
        ];
    }
}
