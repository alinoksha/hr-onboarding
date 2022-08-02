<?php

namespace App\Repositories;

use App\Models\OnboardingProgress;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property OnboardingProgress $model
 */
class OnboardingProgressRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(OnboardingProgress::class);
    }
}
