<?php

namespace App\Models;

use App\Policies\ProjectPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(ProjectPolicy::class)]
class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "status",
        "start_date",
        "deadline",
        "created_by",
        "updated_by",
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "project_user_roles")
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, "updated_by");
    }

    /**
     * Scopes and Helpers
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        // Global viewer (Owner, ops manager)
        if ($user->hasPermission('view_all_projects')) {
            return $query;
        }

        // Project members (Team Lead, Developer, QA)
        return $query->whereHas('members', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    }

    public function hasMember(User $user): bool
    {
        return $this->members()->where('users.id', $user->id)->exists();
    }
    public function memberRole(User $user): ?Role
    {
        $member = $this->members()->where('users.id', $user->id)->first();
        return $member?->pivot?->role;
    }
}
