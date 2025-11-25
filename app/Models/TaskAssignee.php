<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskAssignee extends Pivot
{
    protected $table = 'task_assignees';

    protected $fillable = [
        'user_id',
        'task_id',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}