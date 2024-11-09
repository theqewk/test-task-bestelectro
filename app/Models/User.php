<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'position',
        'email',
        'password',
        'department_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    public function watchedTasks(): MorphToMany
    {
        return $this->morphToMany(Task::class, 'watcher', 'task_watchers');
    }

    public function getVisibleTasks()
    {
        return Task::where(function ($query) {
            $query->where('assignee_id', $this->id)
                ->orWhere(function ($q) {
                    $q->where('department_id', $this->department_id)
                        ->whereNull('assignee_id');
                });
        })
            ->orWhereHas('task_watchers', function ($query) {
                $query->where(function ($q) {
                    $q->where('watcher_type', User::class)
                        ->where('watcher_id', $this->id);
                })->orWhere(function ($q) {
                    $q->where('watcher_type', Department::class)
                        ->where('watcher_id', $this->department_id);
                });
            });
    }

}
