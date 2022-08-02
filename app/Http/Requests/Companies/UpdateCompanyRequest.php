<?php

namespace App\Http\Requests\Companies;

use App\Http\Requests\Request;

class UpdateCompanyRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->entity);
    }

    public function rules(): array
    {
        return [
            'name' => 'string'
        ];
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Company', $this->route('id'));

        parent::validateResolved();
    }
}
