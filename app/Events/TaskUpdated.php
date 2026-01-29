<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use App\Http\Resources\TaskResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskUpdated implements ShouldBroadcast
{
    /**
     * Create a new event instance.
     */
    public function __construct(public $task, public string $action) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new Channel('tasks')];
    }

    public function broadcastAs(): string
    {
        return 'TaskUpdated';
    }

    // public function broadcastWith(): array
    // {        
    //     return [
    //         'action' => $this->action,
    //         'task' => new TaskResource($this->task),
    //     ];
    // }
}
