<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/SignUpView');
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required", "string", "min:3", "max:50"],
            "email" => ["required", "string", "email", "lowercase", "unique:" . User::class],
            "password" => ["required", "string", "min:6", "max:16"]
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password,
        ]);

        $devRole = Role::where('name', 'developer')->first('id');
        $user->roles()->attach($devRole->id);

        Auth::login($user);

        return redirect('/')->with('success', 'Account created successfully!');
    }
}
