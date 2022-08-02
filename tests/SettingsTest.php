<?php

namespace Tests;

class SettingsTest extends TestCase
{
    public function testListSettings(): void
    {
        $response = $this->actingAs($this->admin)->json('get', '/api/settings');

        $response->assertOk();
        $this->assertEqualsFixture('list_settings.json', $response->json());
    }

    public function testListSettingsNoPermission(): void
    {
        $response = $this->actingAs($this->manager)->json('get', '/api/settings');

        $response->assertForbidden();
    }

    public function testListSettingsNotAuth(): void
    {
        $response = $this->json('get', '/api/settings');

        $response->assertUnauthorized();
    }

    public function testUpdateSettings(): void
    {
        $data = $this->getJsonFixture('update_settings.json');

        $response = $this->actingAs($this->admin)->json('put', '/api/settings', $data);

        $response->assertOk();
        $this->assertEqualsFixture('updated_settings.json', $response->json());
    }

    public function testUpdateSettingsNoPermission(): void
    {
        $data = $this->getJsonFixture('update_settings.json');

        $response = $this->actingAs($this->manager)->json('put', '/api/settings', $data);

        $response->assertForbidden();
    }

    public function testUpdateSettingsNotAuth(): void
    {
        $data = $this->getJsonFixture('update_settings.json');

        $response = $this->json('put', '/api/settings', $data);

        $response->assertUnauthorized();
    }
}
