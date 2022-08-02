<?php

namespace App\Models;

class Answer extends BaseModel
{
    protected $casts = [
        'answer' => 'array'
    ];

    protected $fillable = [
        'answer',
        'task_id',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
