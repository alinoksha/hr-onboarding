<?php

namespace Tests;

use Illuminate\Support\Arr;

class AuthTest extends TestCase
{
    const LOGIN_URL = '/api/login';

    public function testLoginAsAdminToHrManagement()
    {
        $response = $this->json('post', self::LOGIN_URL, [
            'email' => $this->admin['email'],
            'password' => $this->password
        ], ['x-app-name' => 'hr_management']);

        $response->assertOk();

        $this->assertEqualsFixture('login.json', Arr::except($response->json(), ['token']));

        $this->assertArrayHasKey('token', $response->json());
    }

    public function testLoginAsManagerToHrManagement()
    {
        $response =  $this->json('post', self::LOGIN_URL, [
            'email' => $this->manager['email'],
            'password' => $this->password
        ], ['x-app-name' => 'hr_management']);

        $response->assertOk();

        $this->assertArrayHasKey('token', $response->json());
    }

    public function testLoginAsEmployeeToHrManagement()
    {
        $response = $this->json('post', self::LOGIN_URL, [
            'email' => $this->employee['email'],
            'password' => $this->password
        ], ['x-app-name' => 'hr_management']);

        $response->assertForbidden();
    }

    public function testLoginAsAdminToHrOnboarding()
    {
        $response = $this->json('post', self::LOGIN_URL, [
            'email' => $this->admin['email'],
            'password' => $this->password
        ], ['x-app-name' => 'hr_onboarding']);

        $response->assertForbidden();
    }

    public function testLoginAsManagerToHrOnboarding()
    {
        $response = $this->json('post', self::LOGIN_URL, [
            'email' => $this->manager['email'],
            'password' => $this->password
        ], ['x-app-name' => 'hr_onboarding']);

        $response->assertForbidden();
    }

    public function testLoginAsEmployeeToHrOnboarding()
    {
        $response = $this->json('post', self::LOGIN_URL, [
            'email' => $this->employee['email'],
            'password' => $this->password
        ], ['X-APP-NAME' => 'hr_onboarding']);

        $response->assertOk();

        $this->assertArrayHasKey('token', $response->json());
    }

    public function testLoginWithoutHeader()
    {
        $response = $this->json('post', self::LOGIN_URL, [
            'email' => $this->admin['email'],
            'password' => $this->password
        ]);

        $response->assertForbidden();
    }

    public function testLoginWrongCredentials()
    {
        $response = $this->json('post', self::LOGIN_URL, [
            'email' => 'wrong@mail.ru',
            'password' => 'wrong password'
        ]);

        $response->assertUnauthorized();
    }

    public function testLoginIncorrectCredentials()
    {
        $response = $this->json('post', self::LOGIN_URL, [
            'email' => 'wrong email',
            'password' => 'wrong password'
        ]);

        $response->assertUnprocessable();
    }

    public function testLoginInvalidAppName()
    {
        $response = $this->json('post', self::LOGIN_URL, [
            'email' => $this->admin['email'],
            'password' => $this->password
        ], ['x-app-name' => 'fdfdf']);

        $response->assertForbidden();
    }
}
