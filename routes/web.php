<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Task\TaskController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, "index"])->name('home');

    Route::get('/projects', [ProjectController::class, "index"])->name('projects.view');
    Route::post('/projects', [ProjectController::class, "store"])->name('projects.view.post');
    Route::delete('/projects/{id}', [ProjectController::class, "destroy"])->name('projects.delete');

    Route::get('/tasks', [TaskController::class, "index"])->name('tasks.view');
    Route::post('/tasks', [TaskController::class, "store"])->name('tasks.view.post');
    Route::delete('/tasks/{id}', [TaskController::class, "destroy"])->name('tasks.view.delete');

    Route::get('/members', [MemberController::class, "index"])->name('members.view');
});



require_once __DIR__ . "/auth.php";
