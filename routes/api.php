<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/sign-up', [AuthController::class, 'register']);
    Route::post('/sign-in', [AuthController::class, 'login']);
    Route::middleware('auth:api')->get('/user', [AuthController::class, 'getUser']);
});

Route::prefix('project')->group(function () {
    Route::post('/create', [ProjectController::class, 'create']);
    Route::delete('/{id}', [ProjectController::class, 'delete']);
    Route::put('/{id}', [ProjectController::class, 'update']);
});

