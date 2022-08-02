<?php

namespace App\Repositories;

use App\Models\Media;
use RonasIT\Support\Repositories\BaseRepository;

class MediaRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Media::class);
    }
}
