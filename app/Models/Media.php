<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends BaseModel
{
    protected $fillable = [
        'user_id',
        'link',
        'name',
        'is_public',
        'company_id'
    ];

    protected $appends = ['source'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSourceAttribute(): string
    {
        return config('app.url') . '/api/media/' . $this->id . '/content';
    }
}
