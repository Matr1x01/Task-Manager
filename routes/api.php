<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);

Route::middleware('auth:api')->group(function (){
    Route::get('projects',[\App\Http\Controllers\ProjectController::class,'getProjects']);
    Route::get('projects/{project_id}',[\App\Http\Controllers\ProjectController::class,'getProjectById']);
    Route::post('projects',[\App\Http\Controllers\ProjectController::class,'createProject']);
    Route::put('projects/{project_id}',[\App\Http\Controllers\ProjectController::class,'editProject']);
    Route::delete('projects/{project_id}',[\App\Http\Controllers\ProjectController::class,'deleteProject']);
});
