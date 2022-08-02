<?php

namespace Tests;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AchievementTest extends TestCase
{
    const ACHIEVEMENT_URL = '/api/achievements';

    private User $adminOfSecondCompany;

    public function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::find(4);
        $this->adminOfSecondCompany = User::find(5);
    }

    public function testCreateAsAdmin(): void
    {
        $data = $this->getJsonFixture('create_achievement.json');

        $response = $this->actingAs($this->admin)->json('post', self::ACHIEVEMENT_URL, $data);

        $response->assertOk();

        $this->assertEqualsFixture('create_achievement.json', $response->json());

        $this->assertDatabaseHas('achievements', $data);
    }

    public function testCreateWithDuplicatedScriptId(): void
    {
        $response = $this->actingAs($this->admin)->json('post', self::ACHIEVEMENT_URL, [
            'script_id' => 1,
            'title' => 'title4',
            'incomplete_cover_id' => 1,
            'complete_cover_id' => 2,
            'incomplete_message' => 'msg4',
            'complete_message' => 'msg4'
        ]);

        $response->assertUnprocessable();

        $this->assertDatabaseMissing('achievements', [
            'id' => 4,
            'script_id' => 1,
            'title' => 'title4'
        ]);
    }

    public function testCreateAsManager(): void
    {
        $data = $this->getJsonFixture('create_achievement.json');

        $response = $this->actingAs($this->manager)->json('post', self::ACHIEVEMENT_URL, $data);

        $response->assertOk();

        $this->assertDatabaseHas('achievements', $data);
    }

    public function testCreateNoPermission(): void
    {
        $response = $this->actingAs($this->employee)->json('post', self::ACHIEVEMENT_URL, [
            'script_id' => 3,
            'title' => 'title3',
            'incomplete_cover_id' => 1,
            'complete_cover_id' => 2,
            'incomplete_message' => 'msg',
            'complete_message' => 'msg'
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('achievements', [
            'id' => 3,
            'script_id' => 3,
            'title' => 'title3',
            'incomplete_cover_id' => 1,
            'complete_cover_id' => 2,
            'incomplete_message' => 'msg',
            'complete_message' => 'msg'
        ]);
    }

    public function testDeleteAsAdmin(): void
    {
        $response = $this->actingAs($this->admin)->json('delete', self::ACHIEVEMENT_URL . '/1');

        $response->assertNoContent();

        $this->assertDatabaseMissing('achievements', [
            'id' => 1
        ]);
    }

    public function testDeleteAsAdminOfOtherCompany(): void
    {
        $response = $this->actingAs($this->adminOfSecondCompany)->json('delete', self::ACHIEVEMENT_URL . '/2');

        $response->assertForbidden();

        $this->assertDatabaseHas('achievements', [
            'id' => 2
        ]);
    }

    public function testDeleteAsManager(): void
    {
        $response = $this->actingAs($this->manager)->json('delete', self::ACHIEVEMENT_URL . '/1');

        $response->assertNoContent();

        $this->assertDatabaseMissing('achievements', [
            'id' => 1
        ]);
    }

    public function testDeleteNoPermission(): void
    {
        $response = $this->actingAs($this->employee)->json('delete', self::ACHIEVEMENT_URL . '/1');

        $response->assertForbidden();
    }

    public function testDeleteNotFound(): void
    {
        $response = $this->actingAs($this->admin)->json('delete', self::ACHIEVEMENT_URL . '/0');

        $response->assertNotFound();
    }

    public function testDeleteInvalidId(): void
    {
        $response = $this->actingAs($this->admin)->json('delete', self::ACHIEVEMENT_URL . '/gg');

        $response->assertNotFound();
    }

    public function testDeleteWithoutId(): void
    {
        $response = $this->actingAs($this->admin)->json('delete', self::ACHIEVEMENT_URL);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testUpdateAsAdmin(): void
    {
        $response = $this->actingAs($this->admin)->json('put', self::ACHIEVEMENT_URL . '/1', [
            'title' => 'title12',
            'incomplete_cover_id' => 1,
            'complete_cover_id' => 2,
            'incomplete_message' => 'msg',
            'complete_message' => 'msg'
        ]);

        $this->assertDatabaseHas('achievements', [
            'id' => 1,
            'title' => 'title12',
            'incomplete_cover_id' => 1,
            'complete_cover_id' => 2,
            'incomplete_message' => 'msg',
            'complete_message' => 'msg'
        ]);

        $response->assertNoContent();
    }

    public function testUpdateAsAdminOfOtherCompany(): void
    {
        $response = $this->actingAs($this->adminOfSecondCompany)->json('put', self::ACHIEVEMENT_URL . '/2', [
            'title' => 'title_new',
            'incomplete_message' => 'msg_new',
            'complete_message' => 'msg_new'
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('achievements', [
            'id' => 2,
            'title' => 'title_new',
            'incomplete_message' => 'msg_new',
            'complete_message' => 'msg_new'
        ]);
    }

    public function testUpdateAsManager(): void
    {
        $response = $this->actingAs($this->manager)->json('put', self::ACHIEVEMENT_URL . '/1', [
            'title' => 'title12',
            'incomplete_cover_id' => 1,
            'complete_cover_id' => 2,
            'incomplete_message' => 'msg',
            'complete_message' => 'msg'
        ]);

        $response->assertNoContent();
    }

    public function testUpdateNotFound(): void
    {
        $response = $this->actingAs($this->admin)->json('put', self::ACHIEVEMENT_URL . '/0', [
            'title' => 'title12',
            'incomplete_cover_id' => 1,
            'complete_cover_id' => 2,
            'incomplete_message' => 'msg',
            'complete_message' => 'msg'
        ]);

        $response->assertNotFound();
    }

    public function testUpdateInvalidId(): void
    {
        $response = $this->actingAs($this->admin)->json('put', self::ACHIEVEMENT_URL . '/gg', [
            'title' => 'title12',
            'incomplete_cover_id' => 1,
            'complete_cover_id' => 2,
            'incomplete_message' => 'msg',
            'complete_message' => 'msg'
        ]);

        $response->assertNotFound();
    }

    public function testUpdateWithoutId(): void
    {
        $response = $this->actingAs($this->admin)->json('put', self::ACHIEVEMENT_URL);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testUpdateNoPermission(): void
    {
        $response = $this->actingAs($this->employee)->json('put', self::ACHIEVEMENT_URL . '/1', [
            'title' => 'title12',
            'incomplete_cover_id' => 1,
            'complete_cover_id' => 2,
            'incomplete_message' => 'msg',
            'complete_message' => 'msg'
        ]);

        $response->assertForbidden();
    }

    public function testGet(): void
    {
        $response = $this->actingAs($this->admin)->json('get', self::ACHIEVEMENT_URL . '/1');

        $response->assertOk();

        $this->assertEqualsFixture('get_achievement.json', $response->json());
    }

    public function testGetNotFound(): void
    {
        $response = $this->actingAs($this->admin)->json('get', self::ACHIEVEMENT_URL . '/0');

        $response->assertNotFound();
    }

    public function testGetInvalidId(): void
    {
        $response = $this->actingAs($this->admin)->json('get', self::ACHIEVEMENT_URL . '/gg');

        $response->assertNotFound();
    }

    public function getSearchFilters(): array
    {
        return [
            [
                'filter' => [],
                'result' => 'search_achievements.json'
            ],
            [
                'filter' => ['scripts_ids' => [1, 3]],
                'result' => 'get_achievement_by_scripts_ids.json'
            ],
            [
                'filter' => [
                    'per_page' => 1,
                    'page' => 2
                ],
                'result' => 'search_achievements_pagination.json',
            ]
        ];
    }

    /**
     * @dataProvider  getSearchFilters
     *
     * @param array $filter
     * @param string $fixture
     */
    public function testSearch(array $filter, string $fixture): void
    {
        $response = $this->actingAs($this->admin)->json('get', self::ACHIEVEMENT_URL, $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public function testGetAllAchievementsAsSuperAdmin(): void
    {
        $response = $this->actingAs($this->superAdmin)->json('get', self::ACHIEVEMENT_URL);

        $response->assertOk();

        $this->assertEqualsFixture('get_all_achievements_super_user.json', $response->json());
    }
}
