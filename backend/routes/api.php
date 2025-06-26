<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\TaskController;
use App\Http\Controllers\Api\v1\TaskHistoryController;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Tasks CRUD
    Route::apiResource('tasks', TaskController::class);

    // Histórico de uma tarefa específica
    Route::get('tasks/{task}/history', [TaskHistoryController::class, 'index']);
});
