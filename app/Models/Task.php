<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'creator_id',
        'assignee_id',
        'department_id'
    ];

    protected $casts = [
        'priority' => 'string',
        'status' => 'string',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function userWatchers(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'watcher', 'task_watchers');
    }

    public function departmentWatchers(): MorphToMany
    {
        return $this->morphedByMany(Department::class, 'watcher', 'task_watchers');
    }

    public function task_watchers()
    {
        return $this->hasMany(TaskWatcher::class);
    }
}
