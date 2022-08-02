<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\Request;
use App\Models\Role;
use App\Models\Task;
use App\Services\ScriptService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateTaskRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'response_type' => 'required|string|in:' . $this->getAvailableTypes(),
            'response_options' => 'nullable|array',
            'response_options.*' => 'string',
            'expected_response' => 'nullable|array',
            'expected_response.*' => 'string',
            'script_id' => 'required|int|exists:scripts,id'
        ];
    }

    private function getAvailableTypes(): string
    {
        return implode(',', Task::TYPES);
    }

    public function validateResolved(): void
    {
        $script = app(ScriptService::class)->find($this->input('script_id'));

        if ($this->user()->role_id === Role::EMPLOYEE) {
            throw new AccessDeniedHttpException('This action is unauthorized.');
        }

        if ($this->user()->role_id !== Role::SUPER_ADMIN && $script->company_id !== $this->user()->company_id) {
            throw new AccessDeniedHttpException('You are not allowed to create an achievement for this script.');
        }

        parent::validateResolved();
    }
}
