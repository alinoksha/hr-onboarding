<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\Request;

class GetMediaRequest extends Request
{
    public function validateResolved(): void
    {
        $this->checkEntityExists('Media', $this->route('id'));

        parent::validateResolved();
    }
}
