<?php

namespace Tests;

use App\Models\Task;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class TaskTest extends TestCase
{
    private User $adminOfAnotherCompany;

    public function setUp(): void
    {
        parent::setUp();

        $this->count = Task::count();
        $this->adminOfAnotherCompany = User::find(4);
    }

    public function testCreate(): void
    {
        $data = $this->getJsonFixture('create_task.json');

        $response = $this->actingAs($this->admin)->json('post', '/api/tasks', $data);

        $response->assertOk();

        $this->assertEqualsFixture('create_task.json', $response->json());
        $this->assertDatabaseHas('tasks', $this->getJsonFixture('create_task.json'));
    }

    public function testCreateForAnotherCompany(): void
    {
        $data = $this->getJsonFixture('create_task.json');

        $response = $this->actingAs($this->adminOfAnotherCompany)->json('post', '/api/tasks', $data);

        $response->assertForbidden()->assertJson(['message' => 'You are not allowed to create an achievement for this script.']);

        $this->assertDatabaseMissing('tasks', $this->getJsonFixture('create_task.json'));
    }

    public function testCreateNoPermission(): void
    {
        $data = $this->getJsonFixture('create_task.json');

        $response = $this->actingAs($this->employee)->json('post', '/api/tasks', $data);

        $response->assertForbidden()->assertJson(['message' => 'This action is unauthorized.']);
        $this->assertDatabaseCount('tasks', $this->count);
    }

    public function testCreateNotAuth(): void
    {
        $data = $this->getJsonFixture('create_task.json');

        $response = $this->json('post', '/api/tasks', $data);

        $response->assertUnauthorized();
        $this->assertDatabaseCount('tasks', $this->count);
    }

    public function testUpdate(): void
    {
        $data = $this->getJsonFixture('update_task.json');

        $response = $this->actingAs($this->admin)->json('put', '/api/tasks/1', $data);

        $response->assertOk();

        $this->assertEqualsFixture('update_task.json', $response->json());
        $this->assertDatabaseHas('tasks', $this->getJsonFixture('updated_task_database.json'));
    }

    public function testUpdateTaskToAnotherCompany(): void
    {
        $data = $this->getJsonFixture('update_task.json');

        $response = $this->actingAs($this->adminOfAnotherCompany)->json('put', '/api/tasks/1', $data);

        $response->assertForbidden();

        $this->assertDatabaseMissing('tasks', $this->getJsonFixture('updated_task_database.json'));
    }

    public function testUpdateNoPermission(): void
    {
        $data = $this->getJsonFixture('update_task.json');

        $response = $this->actingAs($this->employee)->json('post', '/api/tasks', $data);

        $response->assertForbidden();
    }

    public function testUpdateNotAuth(): void
    {
        $data = $this->getJsonFixture('update_task.json');

        $response = $this->json('post', '/api/tasks', $data);

        $response->assertUnauthorized();
    }

    public function testUpdateNotExistingTask(): void
    {
        $data = $this->getJsonFixture('update_task.json');

        $response = $this->actingAs($this->admin)->json('put', '/api/tasks/0', $data);

        $response->assertNotFound()->assertJson(['message' => 'Task does not exist']);
    }

    public function testDelete(): void
    {
        $response = $this->actingAs($this->admin)->json('delete', '/api/tasks/1');

        $response->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => 1]);
    }

    public function testDeleteTaskOfAnotherCompany(): void
    {
        $response = $this->actingAs($this->adminOfAnotherCompany)->json('delete', '/api/tasks/1');

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => 1]);
    }

    public function testDeleteNoPermission(): void
    {
        $response = $this->actingAs($this->employee)->json('delete', '/api/tasks/1');

        $response->assertForbidden();
        $this->assertDatabaseCount('tasks', $this->count);
    }

    public function testDeleteNotAuth(): void
    {
        $response = $this->json('delete', '/api/tasks/1');

        $response->assertUnauthorized();
        $this->assertDatabaseCount('tasks', $this->count);
    }

    public function testDeleteNotExistingTask(): void
    {
        $response = $this->actingAs($this->admin)->json('delete', '/api/tasks/0');

        $response->assertNotFound()->assertJson(['message' => 'Task does not exist']);
    }

    public function getSearchFilters(): array
    {
        return [
            [
                'filter' => [],
                'result' => 'get_tasks.json'
            ],
            [
                'filter' => [
                    'per_page' => 1,
                    'page' => 2
                ],
                'result' => 'get_tasks_pagination.json'
            ],
            [
                'filter' => [
                    'order_by' => 'response_type',
                    'desc' => true
                ],
                'result' => 'get_tasks_order_by_title_desc.json'
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
        $response = $this->actingAs($this->admin)->json('get', '/api/scripts/1/tasks', $filter);

        $response->assertOk();
        $this->assertEqualsFixture($fixture, $response->json());
    }

    public function testGetTasksOfAnotherCompany(): void
    {
        $response = $this->actingAs($this->adminOfAnotherCompany)->json('get', '/api/scripts/1/tasks');
    }

    public function testSearchTasksNoPermission(): void
    {
        $response = $this->actingAs($this->employee)->json('get', '/api/scripts/1/tasks');

        $response->assertForbidden();
    }

    public function testSearchTasksNotAuth(): void
    {
        $response = $this->json('get', '/api/scripts/1/tasks');

        $response->assertUnauthorized();
    }

    public function testSearchNotExistingScriptsTasks(): void
    {
        $response = $this->actingAs($this->admin)->json('get', '/api/scripts/0/tasks');

        $response->assertNotFound()->assertJson(['message' => 'Script does not exist']);
    }

    public function testAnswerTaskRadio(): void
    {
        $response = $this->actingAs($this->admin)->json('post', '/api/tasks/1/answer', ['answer' => ['telegram']]);

        $response->assertNoContent();

        $this->assertDatabaseHas('answers', ['answer' => "[\"telegram\"]", 'user_id' => 1, 'task_id' => 1]);
    }

    public function testAnswerTaskRadioIncorrect(): void
    {
        $this->assertDatabaseHas('onboarding_progress', ['user_id' => 1, 'percent' => '0']);

        $response = $this->actingAs($this->admin)->json('post', '/api/tasks/1/answer', ['answer' => ['slack']]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson(['message' => 'Task failed']);
        $this->assertDatabaseHas('onboarding_progress', ['user_id' => 1, 'percent' => '0']);
    }

    public function testAnswerTaskNoPermission(): void
    {
        $response = $this->actingAs($this->employee)->json('post', '/api/tasks/1/answer', ['answer' => ['telegram']]);

        $response->assertForbidden();
    }

    public function testAnswerTaskMedia(): void
    {
        $this->assertDatabaseHas('onboarding_progress', ['user_id' => 3, 'percent' => '0']);

        $response = $this->actingAs($this->employee)->json('post', '/api/tasks/3/answer', ['answer' => ['1']]);

        $response->assertNoContent();

        $this->assertDatabaseHas('answers', ['answer' => "[\"1\"]", 'user_id' => 3, 'task_id' => 3]);
        $this->assertDatabaseHas('onboarding_progress', ['user_id' => 3, 'percent' => '100.0']);
    }

    public function testAnswerTaskWithNotExistingMedia(): void
    {
        $response = $this->actingAs($this->employee)->json('post', '/api/tasks/3/answer', ['answer' => ['0']]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson(['message' => 'One of the provided media does not exist']);
    }

    public function testAnswerTaskMediaWithNotNumericId(): void
    {
        $response = $this->actingAs($this->employee)->json('post', '/api/tasks/3/answer', ['answer' => ['ghgv']]);

        $response->assertUnprocessable()->assertJson(['message' => 'Answer should contain a valid media_ids']);
    }

    public function testAnswerNotExistingTask(): void
    {
        $response = $this->actingAs($this->admin)->json('post', '/api/tasks/0/answer', ['answer' => ['1']]);

        $response->assertNotFound();
    }
}
