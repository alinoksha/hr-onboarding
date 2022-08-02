<?php

namespace Tests;

class RoleTest extends TestCase
{
    const SEARCH_URL = '/api/roles';

    public function testGetRolesWhenUnauthorized()
    {
        $response = $this->json('get', self::SEARCH_URL);

        $response->assertUnauthorized();
    }

    public function testGetRolesAsEmployee()
    {
        $response = $this->actingAs($this->employee)->get(self::SEARCH_URL);

        $response->assertForbidden();
    }

    public function testGetRolesAsAdmin()
    {
        $response = $this->actingAs($this->admin)->get(self::SEARCH_URL);

        $response->assertOk();

        $this->assertEqualsFixture('get_roles_by_query.json', $response->json());
    }

    public function testGetRolesAsManager()
    {
        $response = $this->actingAs($this->manager)->get(self::SEARCH_URL);

        $response->assertOk();

        $this->assertEqualsFixture('get_roles_by_query.json', $response->json());
    }
}
