<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthenticatedUserController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/SignInView');
    }

    public function store(Request $request)
    {
        $request->validate([
            "email" => ["required", "email", "exists:users,email"],
            "password" => ["required", "string"]
        ]);

        if (!Auth::attempt($request->only(['email', 'password']))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }

        $request->session()->regenerate();

        return redirect('/');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/signin');
    }
}
