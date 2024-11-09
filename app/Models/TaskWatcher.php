<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TaskWatcher extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'watcher_id',
        'watcher_type'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function watcher(): MorphTo
    {
        return $this->morphTo();
    }
}
