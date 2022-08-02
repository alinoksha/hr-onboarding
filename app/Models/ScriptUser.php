<?php

namespace App\Models;

class ScriptUser extends BaseModel
{
    protected $table = 'script_user';

    protected $fillable = [
        'script_id',
        'user_id'
    ];
}
