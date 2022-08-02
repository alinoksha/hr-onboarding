<?php

namespace App\Models;

class Setting extends BaseModel
{
    protected $fillable = [
        'name',
        'type',
        'data',
        'sorting_order',
        'setting_group_id',
        'company_id'
    ];

    protected $hidden = ['setting_group_id'];
}
