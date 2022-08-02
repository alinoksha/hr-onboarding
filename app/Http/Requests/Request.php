<?php

namespace App\Http\Requests;

use RonasIT\Support\BaseRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Request extends BaseRequest
{
    protected $entity;

    protected function checkEntityExists(string $modelName, int $id, bool $withTrashed = false)
    {
        $this->entity = app("App\\Services\\{$modelName}Service")->withTrashed($withTrashed)->find($id);

        if (!$this->entity) {
            throw new NotFoundHttpException($modelName . ' does not exist');
        }
    }
}
