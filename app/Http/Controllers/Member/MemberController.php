<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateMemberRequest;
use App\Http\Requests\Projects\DeleteMemberRequest;
use App\Http\Requests\Projects\UpdateMemberRequest;
use App\Models\User;
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

    public function store(CreateMemberRequest $request)
    {
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "role_id" => $request->role_id,
            "password" => Hash::make('password'),
        ]);

        return back()->with('success', 'New user created successful');
    }

    public function update(UpdateMemberRequest $request, User $user)
    {
        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "role_id" => $request->role_id,
        ]);

        return back()->with('success', 'User updated successful.');
    }

    public function destroy(DeleteMemberRequest $request, User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted successful.');
    }
}
