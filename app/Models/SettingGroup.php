<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SettingGroup extends BaseModel
{
    protected $fillable = [
        'id',
        'name'
    ];

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class)->orderBy('sorting_order');
    }
}
