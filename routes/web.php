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
    Route::post('/projects', [ProjectController::class, "store"])->name('projects.post');
    Route::delete('/projects/{id}', [ProjectController::class, "destroy"])->name('projects.delete');
    Route::put('/projects/{id}', [ProjectController::class, "update"])->name('projects.update');

    Route::get('/tasks', [TaskController::class, "index"])->name('tasks.view');
    Route::post('/tasks', [TaskController::class, "store"])->name('tasks.post');
    Route::delete('/tasks/{id}', [TaskController::class, "destroy"])->name('tasks.delete');
    Route::put('/tasks/{id}', [TaskController::class, "update"])->name('tasks.update');
    Route::patch('/tasks/{id}/update-status', [TaskController::class, "updateStatus"])->name('tasks.update.status');

    Route::get('/members', [MemberController::class, "index"])->name('members.view');
    Route::post('/members', [MemberController::class, "store"])->name('members.post');
    Route::put('/members/{id}', [MemberController::class, "update"])->name('members.update');
    Route::delete('/members/{id}', [MemberController::class, "destroy"])->name('members.delete');
});



require_once __DIR__ . "/auth.php";
