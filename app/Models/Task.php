<?php

namespace App\Models;

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

    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function assignee() : BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
