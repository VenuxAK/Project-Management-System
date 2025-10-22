<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/status', function () {
//     return [
//         'name' => 'Laravel',
//         'version' => app()->version(),
//         'phpVersion' => PHP_VERSION,
//     ];
// });

Route::get('/', function () {
    return Inertia::render('IndexView');
})->name('home');

Route::get('/projects', function () {
    return Inertia::render('Project/Index');
})->name('projects.view');

Route::get('/tasks', function () {
    return Inertia::render('Task/Index');
})->name('tasks.view');

Route::get('/members', function () {
    return Inertia::render('Member/Index');
})->name('members.view');

Route::get('/signin', function () {
    return Inertia::render('Auth/SignInView');
})->name('auth.signin');
Route::get('/signup', function () {
    return Inertia::render('Auth/SignUpView');
})->name('auth.signup');
