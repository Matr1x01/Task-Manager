<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'status',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function subTasks(): HasManyThrough
    {
        return $this->hasManyThrough(SubTask::class, Task::class);
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
