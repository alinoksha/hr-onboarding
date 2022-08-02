<?php

namespace Tests;

use App\Mail\PasswordSetup;
use App\Mail\ResetPassword;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends TestCase
{
    use UserMockTrait;

    protected const USER_URL = '/api/users';
    private int $count;

    public function setUp(): void
    {
        parent::setUp();

        $this->count = User::withTrashed()->count();
    }

    public function testGetUsersWithoutRights()
    {
        $response = $this->actingAs($this->employee)->json('get', self::USER_URL);

        $response->assertForbidden();
    }

    public function getSearchFilters(): array
    {
        return [
            [
                'filter' => [],
                'result' => 'get_all_users.json'
            ],
            [
                'filter' => [
                    'per_page' => 2,
                    'page' => 2
                ],
                'result' => 'get_all_users_pagination.json'
            ],
            [
                'filter' => [
                    'roles_ids' => [3]
                ],
                'result' => 'get_employees.json'
            ],
            [
                'filter' => [
                    'order_by' => 'id',
                    'desc' => true
                ],
                'result' => 'get_all_users_order_by_id_desc.json'
            ],
            [
                'filter' => [
                    'with' => ['scripts']
                ],
                'result' => 'get_all_users_with_scripts.json'
            ],
            [
                'filter' => [
                    'with' => ['scripts', 'onboarding_progress', 'script_progress']
                ],
                'result' => 'get_all_users_with_scripts_and_progress.json'
            ],
            [
                'filter' => [
                    'with_trashed' => true
                ],
                'result' => 'get_active_and_blocked_users.json'
            ],
            [
                'filter' => [
                    'only_trashed' => true
                ],
                'result' => 'get_blocked_users.json'
            ],
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
        $response = $this->actingAs($this->admin)->json('get', self::USER_URL, $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public function testCreate()
    {
        $data = $this->getJsonFixture('create_user.json');

        Mail::fake();
        $this->mockPassword();

        $response = $this->actingAs($this->admin)->json('post', self::USER_URL, $data);

        $response->assertOk();

        Mail::assertSent(PasswordSetup::class);
        $this->assertEqualsFixture('created_user.json', $response->json());
        $this->assertDatabaseHas('users', $this->getJsonFixture('created_user_database.json'));
    }

    public function testCreateWithScripts()
    {
        $data = $this->getJsonFixture('create_user_with_scripts.json');

        Mail::fake();
        $this->mockPassword();

        $response = $this->actingAs($this->admin)->json('post', '/api/users', $data);

        $response->assertOk();

        Mail::assertSent(PasswordSetup::class);
        $this->assertEqualsFixture('created_user.json', $response->json());
        $this->assertDatabaseHas('users', $this->getJsonFixture('created_user_database.json'));
        $this->assertDatabaseHas('script_user', ['user_id' => 9, 'script_id' => 1]);
        $this->assertDatabaseHas('script_user', ['user_id' => 9, 'script_id' => 3]);
    }

    public function testCreateNoPermission()
    {
        $data = $this->getJsonFixture('create_user.json');

        Mail::fake();

        $response = $this->actingAs($this->employee)->json('post', self::USER_URL, $data);

        $response->assertForbidden();

        Mail::assertNotSent(PasswordSetup::class);
        $this->assertDatabaseCount('users', $this->count);
    }

    public function testCreateNotAuth()
    {
        $data = $this->getJsonFixture('create_user.json');

        Mail::fake();

        $response = $this->json('post', self::USER_URL, $data);

        $response->assertUnauthorized();

        Mail::assertNotSent(PasswordSetup::class);
        $this->assertDatabaseCount('users', $this->count);
    }

    public function testCreateSuperAdmin()
    {
        $data = $this->getJsonFixture('create_super_admin.json');

        Mail::fake();

        $response = $this->actingAs($this->admin)->json('post', self::USER_URL, $data);

        $response->assertUnprocessable();

        $response->assertJson(['message' => 'Can not create a user with the administrator role.']);

        Mail::assertNotSent(PasswordSetup::class);
        $this->assertDatabaseCount('users', $this->count);
    }

    public function testCreateAdmin()
    {
        $data = $this->getJsonFixture('create_admin.json');

        Mail::fake();

        $response = $this->actingAs($this->admin)->json('post', self::USER_URL, $data);

        $response->assertUnprocessable();

        Mail::assertNotSent(PasswordSetup::class);
        $this->assertDatabaseCount('users', $this->count);
    }

    public function testCreateUserWithDefaultRole()
    {
        $data = $this->getJsonFixture('create_user_default_role.json');

        Mail::fake();
        $this->mockPassword();

        $response = $this->actingAs($this->admin)->json('post', self::USER_URL, $data);

        $response->assertOk();

        Mail::assertSent(PasswordSetup::class);
        $this->assertEqualsFixture('created_user_default_role.json', $response->json());
        $this->assertDatabaseHas('users', $this->getJsonFixture('created_user_default_role_database.json'));
    }

    public function testUpdateAsSuperAdminOfSuperAdminRole()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->superAdmin)->json('put', self::USER_URL . '/6', $data);

        $response->assertForbidden();
    }

    public function testUpdateYourself()
    {
        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/1', ['first_name' => 'Ivan']);

        $response->assertForbidden();
    }

    public function testUpdateAsAdminOfSuperAdmin()
    {
        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/6', ['first_name' => 'Ivan']);

        $response->assertForbidden();
    }

    public function testUpdateAsAdminOfAdmin()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/7', $data);

        $response->assertNoContent();
    }

    public function testUpdateAdminOfAdminToManager()
    {
        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/7', ['role_id' => Role::MANAGER]);

        $response->assertForbidden();

        $response->assertJson(['message' => "Admin's role can not be changed."]);
    }

    public function testUpdateAdminOfAdminToEmployee()
    {
        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/7', ['role_id' => Role::EMPLOYEE]);

        $response->assertForbidden();

        $response->assertJson(['message' => "Admin's role can not be changed."]);
    }

    public function testUpdateAsAdminOfManager()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/2', $data);

        $response->assertNoContent();
    }

    public function testUpdateAsAdminOfEmployee()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/3', $data);

        $response->assertNoContent();
    }

    public function testUpdateAsManagerOfAdmin()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->manager)->json('put', self::USER_URL . '/1', $data);

        $response->assertForbidden();

        $response->assertJson(['message' => 'Can be changed employee only.']);
    }

    public function testUpdateAsManagerOfManagerToAdmin()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->manager)->json('put', self::USER_URL . '/8', $data);

        $response->assertJson(['message' => 'Can be changed employee only.']);
    }

    public function testUpdateAsManagerOfEmployeeToAdmin()
    {
        $response = $this->actingAs($this->manager)->json('put', self::USER_URL . '/3', ['role_id' => Role::ADMIN]);

        $response->assertForbidden();

        $response->assertJson(['message' => "Admin's role can not be got."]);
    }

    public function testUpdateAsManagerOfEmployeeToManager()
    {
        $response = $this->actingAs($this->manager)->json('put', self::USER_URL . '/3', ['role_id' => Role::MANAGER]);

        $response->assertNoContent();
    }

    public function testUpdateAsManagerOfManagerToEmployee()
    {
        $response = $this->actingAs($this->manager)->json('put', self::USER_URL . '/8', ['role_id' => Role::EMPLOYEE]);

        $response->assertForbidden();

        $response->assertJson(['message' => 'Can be changed employee only.']);
    }

    public function testUpdateAsEmployee()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->employee)->json('put', self::USER_URL . '/1', $data);

        $response->assertForbidden();
    }

    public function testUpdateWithoutRole()
    {
        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/7', ['first_name' => 'Andrey']);

        $response->assertNoContent();
    }

    public function testUpdateWithScripts()
    {
        $data = $this->getJsonFixture('update_user_with_scripts.json');

        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/2', $data);

        $response->assertNoContent();

        $this->assertDatabaseHas('users', $this->getJsonFixture('update_user_database.json'));
        $this->assertDatabaseHas('script_user', ['user_id' => 2, 'script_id' => 1]);
        $this->assertDatabaseHas('script_user', ['user_id' => 2, 'script_id' => 3]);
        $this->assertDatabaseMissing('script_user', ['user_id' => 2, 'script_id' => 2]);
    }

    public function testUpdateWithoutScripts()
    {
        $data = $this->getJsonFixture('update_user_without_scripts.json');

        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/2', $data);

        $response->assertNoContent();

        $this->assertDatabaseMissing('script_user', ['user_id' => 2, 'script_id' => 3]);
    }

    public function testUpdateNotFound()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/0', $data);

        $response->assertNotFound();
    }

    public function testUpdateInvalidId()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', self::USER_URL . '/gg', $data);

        $response->assertNotFound();
    }

    public function testUpdateNoAuth()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->json('put', self::USER_URL . '/1', $data);

        $response->assertUnauthorized();
    }

    public function testUpdateWithoutId()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', self::USER_URL, $data);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testResetPasswordAsAdmin()
    {
        Mail::fake();
        $this->mockPassword();

        $response = $this->actingAs($this->admin)->json('put', '/api/users/' . $this->manager->id . '/reset-password');

        $response->assertNoContent();

        Mail::assertSent(PasswordSetup::class);
        $this->assertDatabaseHas('users', ['id' => $this->manager->id, 'password' => 'password_hash']);
    }

    public function testResetPasswordAsManagerToEmployee()
    {
        Mail::fake();
        $this->mockPassword();

        $response = $this->actingAs($this->manager)->json('put', '/api/users/' . $this->employee->id . '/reset-password');

        $response->assertNoContent();

        Mail::assertSent(PasswordSetup::class);
        $this->assertDatabaseHas('users', ['id' => $this->employee->id, 'password' => 'password_hash']);
    }

    public function testResetPasswordToAdminAsManager()
    {
        Mail::fake();
        $this->mockPassword();

        $response = $this->actingAs($this->manager)->json('put', '/api/users/' . $this->admin->id . '/reset-password');

        $response->assertForbidden();

        Mail::assertNotSent(PasswordSetup::class);
        $this->assertDatabaseMissing('users', ['id' => $this->admin->id, 'password' => 'password_hash']);
    }

    public function testResetPasswordAsEmployee()
    {
        Mail::fake();
        $this->mockPassword();

        $response = $this->actingAs($this->employee)->json('put', '/api/users/' . $this->employee->id . '/reset-password');

        $response->assertForbidden();

        Mail::assertNotSent(PasswordSetup::class);
        $this->assertDatabaseMissing('users', ['id' => $this->employee->id, 'password' => 'password_hash']);
    }

    public function testResetPasswordNotAuth()
    {
        Mail::fake();
        $this->mockPassword();

        $response = $this->json('put', '/api/users/' . $this->employee->id . '/reset-password');

        $response->assertUnauthorized();

        Mail::assertNotSent(PasswordSetup::class);
        $this->assertDatabaseMissing('users', ['id' => $this->employee->id, 'password' => 'password_hash']);
    }

    public function testResetPasswordToNotExistingUser()
    {
        $response = $this->actingAs($this->admin)->json('put', '/api/users/0/reset-password');
        $response
            ->assertNotFound()
            ->assertJson([
                'message' => 'User does not exist'
            ]);
    }

    public function testBlockUserAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/api/users/' . $this->employee->id);

        $response->assertNoContent();

        $this->assertSoftDeleted('users', ['id' => $this->employee->id]);
    }

    public function testSelfBlock()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/api/users/' . $this->admin->id);

        $response->assertForbidden();

        $this->assertNotSoftDeleted('users', ['id' => $this->admin->id]);
    }

    public function testBlockEmployeeAsManager()
    {
        $response = $this->actingAs($this->manager)->json('delete', '/api/users/' . $this->employee->id);

        $response->assertNoContent();

        $this->assertSoftDeleted('users', ['id' => $this->employee->id]);
    }

    public function testBlockAdminAsManager()
    {
        $response = $this->actingAs($this->manager)->json('delete', '/api/users/' . $this->admin->id);

        $response->assertForbidden();

        $this->assertNotSoftDeleted('users', ['id' => $this->admin->id]);
    }

    public function testBlockUserAsEmployee()
    {
        $response = $this->actingAs($this->employee)->json('delete', '/api/users/' . $this->employee->id);

        $response->assertForbidden();

        $this->assertNotSoftDeleted('users', ['id' => $this->employee->id]);
    }

    public function testBlockUserNotAuth()
    {
        $response = $this->json('delete', '/api/users/' . $this->employee->id);

        $response->assertUnauthorized();

        $this->assertNotSoftDeleted('users', ['id' => $this->employee->id]);
    }

    public function testBlockNotExistingUser()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/api/users/0');

        $response
            ->assertNotFound()
            ->assertJson([
                'message' => 'User does not exist'
        ]);
    }

    public function testRestoreUser()
    {
        $this->actingAs($this->admin)->json('delete', '/api/users/' . $this->employee->id);

        $response = $this->actingAs($this->admin)->json('put', '/api/users/' . $this->employee->id . '/restore');

        $response->assertNoContent();

        $this->assertNotSoftDeleted('users', ['id' => $this->employee->id]);
    }

    public function testRestoreEmployeeAsManager()
    {
        $this->actingAs($this->manager)->json('delete', '/api/users/' . $this->employee->id);

        $response = $this->actingAs($this->manager)->json('put', '/api/users/' . $this->employee->id . '/restore');

        $response->assertNoContent();

        $this->assertNotSoftDeleted('users', ['id' => $this->employee->id]);
    }

    public function testRestoreAdminAsManager()
    {
        $response = $this->actingAs($this->manager)->json('put', '/api/users/5/restore');

        $response->assertForbidden();

        $this->assertSoftDeleted('users', ['id' => 5]);
    }

    public function testRestoreUserAsEmployee()
    {
        $this->actingAs($this->admin)->json('delete', '/api/users/' . $this->manager->id);

        $response = $this->actingAs($this->employee)->json('put', '/api/users/' . $this->manager->id . '/restore');

        $response->assertForbidden();

        $this->assertSoftDeleted('users', ['id' => $this->manager->id]);
    }

    public function testRestoreNotExistingUser()
    {
        $response = $this->actingAs($this->admin)->json('put', '/api/users/0/restore');

        $response
            ->assertNotFound()
            ->assertJson([
                'message' => 'User does not exist'
            ]);
    }

    public function testGetProfile()
    {
        $response = $this->actingAs($this->admin)->json('get', '/api/profile');

        $response->assertOk();

        $this->assertEqualsFixture('admin_profile.json', $response->json());
    }

    public function testGetProfileWithScriptsAndProgress()
    {
        $response = $this->actingAs($this->manager)->json('get', '/api/profile', [
            'with' => [
                'scripts',
                'onboarding_progress',
                'script_progress'
            ]
        ]);

        $response->assertOk();

        $this->assertEqualsFixture('manager_profile_with_scripts_and_progress.json', $response->json());
    }

    public function testGetProfileWithTasksAndAnswers()
    {
        $response = $this->actingAs($this->manager)->json('get', '/api/profile', [
            'with' => [
                'tasks',
                'answers'
            ]
        ]);

        $response->assertOk();

        $this->assertEqualsFixture('manager_profile_with_tasks_and_answers.json', $response->json());
    }

    public function testGetProfileNotAuth()
    {
        $response = $this->json('get', '/api/profile');

        $response->assertUnauthorized();
    }

    public function testUpdateProfile()
    {
        $data = $this->getJsonFixture('update_profile.json');
        $response = $this->actingAs($this->admin)->json('put', '/api/profile', $data);

        $response->assertOk();

        $this->assertEqualsFixture('updated_profile.json', $response->json());
        $this->assertDatabaseHas('users', $this->getJsonFixture('updated_profile_database.json'));
    }

    public function testForgotPassword()
    {
        Mail::fake();

        $response = $this->json('post', '/api/forgot-password', ['email' => $this->admin->email]);

        $response->assertNoContent();

        Mail::assertSent(ResetPassword::class, function ($mail) {
            return $mail->hasTo($this->admin->email);
        });

        $this->assertDatabaseHas('password_resets', ['email' => $this->admin->email]);
    }

    public function testRestorePassword()
    {
        $response = $this->json('post', '/api/restore-password/' . '12345678910', [
            'email' => $this->employee->email,
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertNoContent();

        $this->assertDatabaseMissing('password_resets', ['email' => $this->employee->email]);
    }

    public function testRestorePasswordInvalidToken()
    {
        $response = $this->json('post', '/api/restore-password/' . '12345678910', [
            'email' => $this->manager->email,
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertUnprocessable();

        $this->assertDatabaseHas('password_resets', ['email' => $this->manager->email]);

        $response->assertJson(['message' => 'Invalid token']);
    }

    public function testRestorePasswordWithoutToken()
    {
        $response = $this->json('post', '/api/restore-password' . '12345678910', [
            'email' => $this->employee->email,
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('password_resets', ['email' => $this->employee->email]);
    }
}
