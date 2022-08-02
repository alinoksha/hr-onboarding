<?php

namespace App\Http\Requests\Scripts;

use App\Http\Requests\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateScriptRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|unique:scripts|min:1|max:255',
            'description' => 'required|string|min:1',
            'cover_id' => 'required|exists:media,id',
            'company_id' => 'int|exists:companies,id'
        ];
    }

    public function validateResolved(): void
    {
        if (!$this->user()->company_id && !$this->has('company_id')) {
            throw new UnprocessableEntityHttpException('Cannot create script without a company_id');
        }
        parent::validateResolved();
    }
}
