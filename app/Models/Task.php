<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use App\Models\TaskAssignee;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'description',
        'status',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function assignedUsers() {
        return $this->belongsToMany(User::class, 'task_assignees')
                ->using(TaskAssignee::class)
                ->withTimestamps();
    }
}
