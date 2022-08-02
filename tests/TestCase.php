<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use RonasIT\Support\Tests\TestCase as BaseTestCase;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCaseTrait;

abstract class TestCase extends BaseTestCase
{
    use AutoDocTestCaseTrait;

    protected $admin;
    protected $manager;
    protected $employee;
    protected $superAdmin;
    protected $password = '123456789';

    protected $truncateExceptTables = [
        'migrations', 'password_resets', 'roles'
    ];

    protected $prepareSequencesExceptTables = [
        'migrations', 'password_resets', 'settings', 'roles'
    ];

    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->loadEnvironmentFrom('.env.testing');
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->manager = User::find(2);
        $this->employee = User::find(3);
        $this->superAdmin = User::find(6);
    }
}
