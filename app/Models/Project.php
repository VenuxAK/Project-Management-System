<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "project_user");
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
