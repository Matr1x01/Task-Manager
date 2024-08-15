<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('projects', [ProjectController::class, 'getProjects']);
    Route::get('projects/{project_id}', [ProjectController::class, 'getProjectById']);
    Route::post('projects', [ProjectController::class, 'createProject']);
    Route::put('projects/{project_id}', [ProjectController::class, 'editProject']);
    Route::delete('projects/{project_id}', [ProjectController::class, 'deleteProject']);
    Route::get('projects/{project_id}/report', [ProjectController::class, 'getProjectReport']);

    Route::prefix('projects/{project_id}')->group(function () {
        Route::get('tasks', [TaskController::class, 'getTasks']);
        Route::get('tasks/{task_id}', [TaskController::class, 'getTaskById']);
        Route::post('tasks', [TaskController::class, 'createTask']);
        Route::put('tasks/{task_id}', [TaskController::class, 'editTask']);
        Route::delete('tasks/{task_id}', [TaskController::class, 'deleteTask']);
        Route::post('tasks/upload', [TaskController::class, 'uploadTasks']);

        Route::prefix('tasks/{task_id}')->group(function () {
            Route::get('subtasks', [TaskController::class, 'getSubtasks']);
            Route::get('subtasks/{subtask_id}', [TaskController::class, 'getSubtaskById']);
            Route::post('subtasks', [TaskController::class, 'createSubtask']);
            Route::put('subtasks/{subtask_id}', [TaskController::class, 'editSubtask']);
            Route::delete('subtasks/{subtask_id}', [TaskController::class, 'deleteSubtask']);
        });
    });

});
