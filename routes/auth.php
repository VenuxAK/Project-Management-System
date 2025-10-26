<?php

use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/signin', [AuthenticatedUserController::class, "index"])->name('auth.signin');
    Route::post('/signin', [AuthenticatedUserController::class, "store"]);

    Route::get('/signup', [RegisteredUserController::class, "index"])->name('auth.signup');
    Route::post('/signup', [RegisteredUserController::class, "store"]);
});

Route::middleware('auth')->group(function () {
    Route::post('/signout', [AuthenticatedUserController::class, "destroy"])->name('auth.signout');
});
