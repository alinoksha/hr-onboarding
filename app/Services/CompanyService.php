<?php

namespace App\Services;

use App\Repositories\CompanyRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property CompanyRepository $repository
 */
class CompanyService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(CompanyRepository::class);
    }
}
