<?php

namespace App\Http\Requests\Achievements;

use App\Http\Requests\Request;

class DeleteAchievementRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('delete', $this->entity);
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Achievement', $this->route('id'));

        parent::validateResolved();
    }
}
