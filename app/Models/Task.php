<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Task extends BaseModel
{
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_TEXT = 'text';
    const TYPE_MEDIA = 'media';

    const TYPES = [
        self::TYPE_RADIO,
        self::TYPE_CHECKBOX,
        self::TYPE_TEXT,
        self::TYPE_MEDIA
    ];

    protected $casts = [
        'response_options' => 'array',
        'expected_response' => 'array'
    ];

    protected $fillable = [
        'title',
        'content',
        'response_type',
        'response_options',
        'expected_response',
        'script_id'
    ];

    protected $hidden = [
        'laravel_through_key'
    ];

    public function script(): BelongsTo
    {
        return $this->belongsTo(Script::class);
    }

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
}
