<?php

namespace App\Http\Requests\Achievements;

use App\Http\Requests\Request;

class UpdateAchievementRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->entity);
    }

    public function rules(): array
    {
        return [
            'title' => 'string|min:1|max:255|unique:achievements,title,' . $this->id,
            'incomplete_cover_id' => 'exists:media,id',
            'complete_cover_id' => 'exists:media,id',
            'incomplete_message' => 'string',
            'complete_message' => 'string'
        ];
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Achievement', $this->route('id'));

        parent::validateResolved();
    }
}
