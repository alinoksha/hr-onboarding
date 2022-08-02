<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Achievement extends BaseModel
{
    protected $fillable = [
        'script_id',
        'title',
        'incomplete_cover_id',
        'complete_cover_id',
        'incomplete_message',
        'complete_message'
    ];

    protected $hidden = ['pivot'];

    public function company(): HasOneThrough
    {
        return $this->hasOneThrough(
            Company::class,
            Script::class,
            'id',
            'id',
            'script_id',
            'company_id'
        );
    }

    public function script(): BelongsTo
    {
        return $this->belongsTo(Script::class);
    }
}
