<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => fn() => $request->user()
                    ? $request->user()->only('id', 'name', 'email', 'profile_picture')
                    : null,
                'roles' => fn() => $this->sharedRoles($request),
                'permissions' => fn() => $this->sharedPermissions($request),
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'warning' => fn() => $request->session()->get('warning'),
                'info' => fn() => $request->session()->get('info'),
            ],
        ];
    }

    /**
     * Global roles of the authenticated user, shared for UI display purposes.
     */
    private function sharedRoles(Request $request): array
    {
        if (! $user = $request->user()) {
            return [];
        }

        return $user->loadMissing('roles')->roles
            ->where('scope', 'global')
            ->map(fn($role) => $role->only('id', 'name', 'scope'))
            ->values()
            ->all();
    }

    /**
     * Permission names granted by the user's global roles.
     *
     * Project-scoped permissions are intentionally excluded: they are only
     * meaningful within a project context and must be resolved server-side.
     */
    private function sharedPermissions(Request $request): array
    {
        if (! $user = $request->user()) {
            return [];
        }

        return $user->loadMissing('roles.permissions')->roles
            ->where('scope', 'global')
            ->flatMap(fn($role) => $role->permissions)
            ->unique('id')
            ->pluck('name')
            ->values()
            ->all();
    }
}
