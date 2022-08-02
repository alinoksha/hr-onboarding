<?php

namespace Tests;

use App\Services\UserService;
use RonasIT\Support\Traits\MockClassTrait;

trait UserMockTrait
{
    use MockClassTrait;

    public function mockPassword()
    {
        $this->mockClass(UserService::class, [
            ['method' => 'generatePassword', 'result' => ['password', 'password_hash']]
        ]);
    }
}
