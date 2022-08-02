<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Script extends BaseModel
{
    protected $fillable = [
        'title',
        'description',
        'cover_id',
        'company_id'
    ];

    protected $hidden = ['pivot'];

    public function achievement(): HasOne
    {
        return $this->hasOne(Achievement::class);
    }
}
