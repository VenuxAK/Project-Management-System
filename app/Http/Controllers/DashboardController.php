<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return inertia('IndexView', [
            "projects" => Project::query()->visibleTo($request->user())->latest()->get(),
        ]);
    }
}
