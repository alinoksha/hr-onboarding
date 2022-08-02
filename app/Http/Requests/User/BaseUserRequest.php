<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Services\ScriptService;
use App\Traits\AuthorizationTrait;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BaseUserRequest extends Request
{
    use AuthorizationTrait;

    protected function checkScriptsExists(): void
    {
        $scriptsIds = $this->input('scripts', []);

        $scripts = app(ScriptService::class)->getByList($scriptsIds);

        if (count($scripts) !== count(array_unique($scriptsIds))) {
            throw new UnprocessableEntityHttpException('One of provided scripts does not exists.');
        }
    }

    protected function checkRole(): void
    {
        if (!empty($this->role_id) && $this->isAnyAdmin($this->role_id)) {
            throw new UnprocessableEntityHttpException(
                'Can not create a user with the administrator role.'
            );
        }
    }
}
