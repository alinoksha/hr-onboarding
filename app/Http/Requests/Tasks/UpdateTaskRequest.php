<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\Request;
use App\Models\Task;

class UpdateTaskRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->entity);
    }

    public function rules(): array
    {
        return [
            'title' => 'string|max:255',
            'content' => 'string',
            'response_type' => 'string|in:' . $this->getAvailableTypes(),
            'response_options' => 'nullable|array',
            'response_options.*' => 'string',
            'expected_response' => 'nullable|array',
            'expected_response.*' => 'string',
            'script_id' => 'int|exists:scripts,id'
        ];
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Task', $this->route('id'));

        parent::validateResolved();
    }

    private function getAvailableTypes(): string
    {
        return implode(',', Task::TYPES);
    }
}
