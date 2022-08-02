<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class GetMediaContentRequest extends Request
{
    public function validateResolved(): void
    {
        $this->checkEntityExists('Media', $this->route('id'));

        if (!$this->entity->is_public && !Auth::guard('sanctum')->check()) {
            throw new AuthenticationException();
        }

        parent::validateResolved();
    }
}
