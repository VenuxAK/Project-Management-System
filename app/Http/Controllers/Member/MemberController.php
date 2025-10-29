<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class MemberController extends Controller
{
    public function index()
    {
        return Inertia::render('Member/Index', [
            'members' => User::where('role_id', "!=", 1)->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        // Make Validation request
        $request->validate([
            "name" => ["required", "string", "min:3", "max:50"],
            "email" => ["required", "string", "email", "lowercase", "unique:" . User::class],
            "role_id" => ["required", "in:2,3"]
        ]);

        $member = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "role_id" => $request->role_id,
            "password" => Hash::make('password'),
        ]);

        return back()->with('success', 'New user created successful');
    }
    public function update(Request $request, $id)
    {
        $member = User::findOrFail($id);

        $request->validate([
            "name" => ["required", "string", "min:3", "max:50"],
            "email" => ["required", "string", "email", "lowercase", "exists:" . User::class],
            "role_id" => ["required", "in:2,3"]
        ]);

        $member->update([
            "name" => $request->name,
            "email" => $request->email,
            "role_id" => $request->role_id,
        ]);

        return back()->with('success', 'User updated successful.');
    }
    public function destroy(Request $request, $id)
    {
        $member = User::findOrFail($id);

        $member->delete();

        return back()->with('success', 'User deleted successful.');
    }
}
