<?php

use App\Http\Controllers\Project\ProjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('IndexView');
    })->name('home');

    Route::get('/projects', [ProjectController::class, "index"])->name('projects.view');
    Route::post('/projects', [ProjectController::class, "store"])->name('projects.view.post');
    Route::delete('/projects/{id}', [ProjectController::class, "destroy"])->name('projects.delete');

    Route::get('/tasks', function () {
        return Inertia::render('Task/Index');
    })->name('tasks.view');

    Route::get('/members', function () {
        return Inertia::render('Member/Index');
    })->name('members.view');
});



require_once __DIR__ . "/auth.php";
