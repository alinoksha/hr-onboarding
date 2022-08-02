<?php

namespace App\Http\Requests\User;

class GetProfileRequest extends BaseUserRequest
{
    public function rules(): array
    {
        return [
            'with' => 'array',
            'with.*' => 'string|in:' . $this->availableRelations()
        ];
    }

    private function availableRelations(): string
    {
        $availableRelations = [
            'scripts',
            'onboarding_progress',
            'script_progress',
            'tasks',
            'answers'
        ];

        return implode(',', $availableRelations);
    }
}
