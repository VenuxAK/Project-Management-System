<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "priority",
        "status",
        "start_date",
        "due_date",
        "project_id",
        "assigned_to",
        "created_by",
        "updated_by",
    ];

    /**
     * Local Scopes
     */
    #[Scope]
    public function scopeForUser(Builder $query, User $user)
    {
        $query->when($user->isAdministrator() ?? false, function ($query) {
            $query->with('assignee');
        });

        $query->when($user->isProjectManager() ?? false, function ($query) use ($user) {
            $query->with('assignee')->where('created_by', "=", $user->id);
        });

        $query->when($user->isEmployee() ?? false, function ($query) use ($user) {
            $query->with('assignee')->where('assigned_to', "=", $user->id);
        });

        return $query;
    }


    /**
     * Relationships
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
