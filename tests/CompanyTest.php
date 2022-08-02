<?php

namespace Tests;

use App\Models\User;

class CompanyTest extends TestCase
{
    private int $count;

    public function setUp(): void
    {
        parent::setUp();

        $this->count = User::withTrashed()->count();
    }

    public function testRegister(): void
    {
        $data = $this->getJsonFixture('register.json');

        $response = $this->json('post', '/api/register', $data);

        $response->assertNoContent();

        $this->assertDatabaseHas('companies' ,['name' => 'test company']);
        $this->assertDatabaseCount('users', $this->count + 1);

    }

    /*public function testUpdate(): void
    {
        $response = $this->actingAs();
    }*/
}
