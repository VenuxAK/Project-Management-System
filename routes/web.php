<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Task\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, "index"])->name('home');

    Route::get('/projects', [ProjectController::class, "index"])->name('projects.view');
    Route::post('/projects', [ProjectController::class, "store"])->name('projects.post');
    Route::put('/projects/{project}', [ProjectController::class, "update"])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, "destroy"])->name('projects.delete');

    Route::get('/tasks', [TaskController::class, "index"])->name('tasks.view');
    Route::post('/tasks', [TaskController::class, "store"])->name('tasks.post');
    Route::delete('/tasks/{task}', [TaskController::class, "destroy"])->name('tasks.delete');
    Route::put('/tasks/{task}', [TaskController::class, "update"])->name('tasks.update');
    Route::patch('/tasks/{task}/update-status', [TaskController::class, "updateStatus"])->name('tasks.update.status');

    Route::get('/members', [MemberController::class, "index"])->name('members.view');
    Route::post('/members', [MemberController::class, "store"])->name('members.post');
    Route::put('/members/{user}', [MemberController::class, "update"])->name('members.update');
    Route::delete('/members/{user}', [MemberController::class, "destroy"])->name('members.delete');
});



require_once __DIR__ . "/auth.php";
