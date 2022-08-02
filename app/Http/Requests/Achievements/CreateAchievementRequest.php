<?php

namespace App\Http\Requests\Achievements;

use App\Http\Requests\Request;
use App\Models\Role;
use App\Services\ScriptService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateAchievementRequest extends Request
{
    public function rules(): array
    {
        return [
            'script_id' => 'required|exists:scripts,id|unique:achievements',
            'title' => 'required|string|min:1|max:255|unique:achievements',
            'incomplete_cover_id' => 'required|exists:media,id',
            'complete_cover_id' => 'required|exists:media,id',
            'incomplete_message' => 'required|string',
            'complete_message' => 'required|string'
        ];
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
