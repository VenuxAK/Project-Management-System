<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Services\Projects\ProjectAccessService;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture'
    ];

    // protected $with = ['roles'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Local Scopes
     */
    #[Scope]
    // public function scopeOwner(Builder $query): Builder
    // {
    //     return $query->with('roles')->whereHas('roles', function (Builder $query) {
    //         $query->where('name', 'like', 'owner');
    //     });
    // }
    // public function scopeOperationManager(Builder $query): Builder
    // {
    //     return $query->with('roles')->whereHas('roles', function (Builder $query) {
    //         $query->where('name', 'like', 'operation_manager');
    //     });
    // }
    // public function scopeProjectLeader(Builder $query): Builder
    // {
    //     return $query->with('roles')->whereHas('roles', function (Builder $query) {
    //         $query->where('name', 'like', 'project_lead');
    //     });
    // }
    public function scopeEmployee(Builder $query): Builder
    {
        return $query->with('roles')->whereHas('roles', function (Builder $query) {
            $query->where('name', 'like', 'developer')
                ->orWhere('name', 'like', 'qa');
        });
    }


    /*********************************
     * Relationships
     *********************************/

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, "user_roles");
    }

    // Project scope roles
    public function projectRoles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'project_user_roles')
            ->withPivot('project_id')
            ->withTimestamps();
    }

    // User's projects
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, "project_user_roles")
            ->withPivot('role_id')
            ->withTimestamps();
    }

    // The project user created
    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    // The project user updated
    public function updatedProjects(): HasMany
    {
        return $this->hasMany(Project::class, "updated_by");
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Helpers
     */
    public function hasRole(string $roleName, ?Project $project = null): bool
    {
        // Global role check
        if ($project === null) {
            return $this->roles()->where('name', $roleName)->exists();
        }

        // Project-scoped role check
        return $this->projectRoles()->where('name', $roleName)
            ->wherePivot('project_id', $project->id)->exists();
    }

    public function hasPermission(string $permission, ?Project $project = null): bool
    {
        return app(ProjectAccessService::class)
            ->can($this, $permission, $project);
    }

    //
    public function hasGlobalPermission(string $permission): bool
    {
        return $this->roles()->whereHas(
            'permissions',
            fn($query) => $query->where('name', $permission)
        )->exists();
    }
}
