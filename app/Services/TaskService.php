<?php

namespace App\Services;

use App\Models\Task;
use App\Events\TaskUpdated;

class TaskService
{
    public function createTask(array $data)
    {
        $task = Task::create($data);
        
        broadcast(new TaskUpdated($task, 'created'));

        return $task;
    }

    public function updateTask(Task $task, array $data)
    {
        $task->update($data);

        broadcast(new TaskUpdated($task, 'updated'));

        return $task;
    }

    public function deleteTask(Task $task): void
    {
        broadcast(new TaskUpdated($task, 'deleted'));

        $task->delete();
    }
}
