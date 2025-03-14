<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/sign-up', [AuthController::class, 'register']);
    Route::post('/sign-in', [AuthController::class, 'login']);
    Route::middleware('auth:api')->get('/account', [AuthController::class, 'getUser']);
    Route::middleware('auth:api')->get('/accounts', [AuthController::class, 'getAllUsers']);
});

Route::prefix('project')->group(function () {
    Route::post('/create', [ProjectController::class, 'create']);
    Route::delete('/{id}', [ProjectController::class, 'delete']);
    Route::put('/{id}', [ProjectController::class, 'update']);
    Route::get('/{accountId}', [ProjectController::class, 'getProjectsByAccountId']);
    Route::get('/assigned-accounts/{projectId}', [ProjectController::class, 'getAssignedAccounts']);
});

Route::prefix('task')->group(function () {
    Route::post('/create', [TaskController::class, 'create']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'delete']);
    Route::get('/statuses', [TaskController::class, 'getStatuses']);
    Route::get('/assigned/{accountId}', [TaskController::class, 'getTasksByAssignedTo']);
});


