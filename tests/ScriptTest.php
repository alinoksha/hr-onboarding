<?php

namespace Tests;

use Symfony\Component\HttpFoundation\Response;

class ScriptTest extends TestCase
{
    const SCRIPT_URL = '/api/scripts';

    public function testCreateAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('post', self::SCRIPT_URL, [
            'title' => 'title3',
            'description' => 'descr2',
            'cover_id' => '1'
        ]);

        $response->assertOk();

        $this->assertEqualsFixture('create_script.json', $response->json());

        $this->assertDatabaseHas('scripts', [
            'id' => 4,
            'title' => 'title3',
            'description' => 'descr2',
            'cover_id' => 1
        ]);
    }

    public function testCreateAsManager()
    {
        $response = $this->actingAs($this->manager)->json('post', self::SCRIPT_URL, [
            'title' => 'title3',
            'description' => 'descr2',
            'cover_id' => '1'
        ]);

        $response->assertOk();
    }

    public function testCreateNoPermission()
    {
        $response = $this->actingAs($this->employee)->json('post', self::SCRIPT_URL, [
            'title' => 'title3',
            'description' => 'descr2',
            'cover_id' => '1'
        ]);

        $response->assertForbidden();
    }

    public function testDeleteAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('delete', self::SCRIPT_URL . '/1');

        $response->assertNoContent();

        $this->assertDatabaseMissing('scripts', [
            'id' => 1
        ]);

        $this->assertDatabaseMissing('achievements', [
            'id' => 1
        ]);
    }

    public function testDeleteAsManager()
    {
        $response = $this->actingAs($this->manager)->json('delete', self::SCRIPT_URL . '/1');

        $response->assertNoContent();
    }

    public function testDeleteNoPermission()
    {
        $response = $this->actingAs($this->employee)->json('delete', self::SCRIPT_URL . '/1');

        $response->assertForbidden();
    }

    public function testDeleteNotFound()
    {
        $response = $this->actingAs($this->admin)->json('delete', self::SCRIPT_URL . '/0');

        $response->assertNotFound();
    }

    public function testDeleteInvalidId()
    {
        $response = $this->actingAs($this->admin)->json('delete', self::SCRIPT_URL . '/gg');

        $response->assertNotFound();
    }

    public function testDeleteWithoutId()
    {
        $response = $this->actingAs($this->admin)->json('delete', self::SCRIPT_URL);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testGet()
    {
        $response = $this->actingAs($this->admin)->json('get', self::SCRIPT_URL . '/1');

        $response->assertOk();

        $this->assertEqualsFixture('get_script.json', $response->json());
    }

    public function testGetNotFound()
    {
        $response = $this->actingAs($this->admin)->json('get', self::SCRIPT_URL . '/0');

        $response->assertNotFound();
    }

    public function testGetInvalidId()
    {
        $response = $this->actingAs($this->admin)->json('get', self::SCRIPT_URL . '/gg');

        $response->assertNotFound();
    }

    public function getSearchFilters(): array
    {
        return [
            [
                'filter' => [],
                'result' => 'search_scripts.json'
            ],
            [
                'filter' => ['with' => ['achievement']],
                'result' => 'search_scripts_with_achievements.json'
            ],
            [
                'filter' => ['query' => 'title1'],
                'result' => 'get_script_by_query.json'
            ],
            [
                'filter' => [
                    'per_page' => 1,
                    'page' => 2
                ],
                'result' => 'search_scripts_pagination.json',
            ]
        ];
    }

    /**
     * @dataProvider  getSearchFilters
     *
     * @param array $filter
     * @param string $fixture
     */
    public function testSearch(array $filter, string $fixture)
    {
        $response = $this->actingAs($this->admin)->json('get', self::SCRIPT_URL, $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public function testUpdateAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('put', self::SCRIPT_URL . '/1', [
            'title' => 'title12',
            'description' => 'descr12',
            'cover_id' => '1'
        ]);

        $response->assertNoContent();
    }

    public function testUpdateAsManager()
    {
        $response = $this->actingAs($this->manager)->json('put', self::SCRIPT_URL . '/1', [
            'title' => 'title12',
            'description' => 'descr12',
            'cover_id' => '1'
        ]);

        $response->assertNoContent();
    }

    public function testUpdateNotFound()
    {
        $response = $this->actingAs($this->admin)->json('put', self::SCRIPT_URL . '/0', [
            'title' => 'title12',
            'description' => 'descr12',
            'cover_id' => '1'
        ]);

        $response->assertNotFound();
    }

    public function testUpdateInvalidId()
    {
        $response = $this->actingAs($this->admin)->json('put', self::SCRIPT_URL . '/gg', [
            'title' => 'title12',
            'description' => 'descr12',
            'cover_id' => '1'
        ]);

        $response->assertNotFound();
    }

    public function testUpdateNoPermission()
    {
        $response = $this->actingAs($this->employee)->json('put', self::SCRIPT_URL . '/1', [
            'title' => 'title',
            'description' => 'descr',
            'cover_id' => '1'
        ]);

        $response->assertForbidden();
    }
}
