<?php

namespace App\Services;

use App\Repositories\OnboardingProgressRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property OnboardingProgressRepository $repository
 */
class OnboardingProgressService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(OnboardingProgressRepository::class);
    }
}
