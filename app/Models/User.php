<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use RonasIT\Support\Traits\ModelTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, ModelTrait, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'date_of_birth',
        'phone',
        'position',
        'starts_on',
        'role_id',
        'hr_id',
        'manager_id',
        'lead_id',
        'avatar_id',
        'company_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hr(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scripts(): BelongsToMany
    {
        return $this->belongsToMany(Script::class);
    }

    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Task::class,
            ScriptUser::class,
            'user_id',
            'script_id',
            'id',
            'script_id'
        );
    }

    public function onboarding_progress(): HasOne
    {
        return $this->hasOne(OnboardingProgress::class);
    }

    public function script_progress(): HasMany
    {
        return $this->hasMany(ScriptProgress::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
