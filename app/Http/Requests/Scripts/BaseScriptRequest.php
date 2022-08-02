<?php

namespace App\Http\Requests\Scripts;

use App\Http\Requests\Request;
use App\Services\ScriptService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseScriptRequest extends Request
{
    protected function checkScriptExists(): void
    {
        $script = app(ScriptService::class)->find($this->route('id'));

        if (empty($script)) {
            throw new NotFoundHttpException("Script doesn't exists");
        }
    }

    public function validateResolved(): void
    {
        $this->checkScriptExists();

        parent::validateResolved();
    }
}
