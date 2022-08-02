<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ScriptController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\Setting;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Models\Role;
use App\Http\Controllers\MediaController;
use App\Models\User;
use App\Models\Script;
use App\Models\Achievement;

Route::post('/login', [UserController::class, 'login']);

Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/restore-password/{token}', [UserController::class, 'restorePassword']);

Route::post('/register', [CompanyController::class, 'register']);

Route::get('/media/{id}/content', [MediaController::class, 'getContentById'])->whereNumber('id');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [UserController::class, 'logout']);

    Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->whereNumber('id');
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/users', [UserController::class, 'search'])->can('search', User::class);
    Route::post('/users', [UserController::class, 'create'])->can('create', User::class);
    Route::put('/users/{id}', [UserController::class, 'update'])->whereNumber('id');
    Route::delete('/users/{id}', [UserController::class, 'block'])->whereNumber('id');
    Route::put('/users/{id}/restore', [UserController::class, 'restore'])->whereNumber('id');
    Route::put('/profile', [UserController::class, 'updateProfile']);

    Route::get('/roles', [RoleController::class, 'search'])->can('search', Role::class);

    Route::post('/media', [MediaController::class, 'create']);
    Route::delete('/media/{id}', [MediaController::class, 'delete'])->where(['id' => '[0-9]+']);
    Route::get('/media/{id}', [MediaController::class, 'getById'])->where(['id' => '[0-9]+']);

    Route::post('/scripts', [ScriptController::class, 'create'])->can('create', Script::class);
    Route::put('/scripts/{id}', [ScriptController::class, 'update'])->whereNumber('id');
    Route::delete('/scripts/{id}', [ScriptController::class, 'delete'])->whereNumber('id');
    Route::get('/scripts/{id}', [ScriptController::class, 'get'])->whereNumber('id');
    Route::get('/scripts', [ScriptController::class, 'search']);

    Route::post('/achievements', [AchievementController::class, 'create']);
    Route::put('/achievements/{id}', [AchievementController::class, 'update'])->whereNumber('id');
    Route::delete('/achievements/{id}', [AchievementController::class, 'delete'])->whereNumber('id');
    Route::get('/achievements/{id}', [AchievementController::class, 'get'])->whereNumber('id');
    Route::get('/achievements', [AchievementController::class, 'search']);

    Route::get('/settings', [SettingController::class, 'list'])->can('list', Setting::class);
    Route::put('/settings', [SettingController::class, 'update']);

    Route::post('/tasks', [TaskController::class, 'create']);
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->whereNumber('id');
    Route::delete('/tasks/{id}', [TaskController::class, 'delete'])->whereNumber('id');
    Route::get('/scripts/{id}/tasks', [TaskController::class, 'search'])->whereNumber('id');
    Route::post('/tasks/{id}/answer', [TaskController::class, 'answer'])->whereNumber('id');

    Route::put('/companies/{id}', [CompanyController::class, 'update'])->whereNumber('id');
});
