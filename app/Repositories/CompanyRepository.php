<?php

namespace App\Repositories;

use App\Models\Company;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property Company $model
 */
class CompanyRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Company::class);
    }
}
