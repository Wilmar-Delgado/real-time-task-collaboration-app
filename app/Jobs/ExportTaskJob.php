<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportTaskJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Tasks export started...');

        // fetch all tasks
        $tasks = Task::all();

        // Simulate heavy processing (in secs)
        sleep(10);

        Log::info('Tasks exported successfully.', ['task_count' => $tasks->count()]);
    }
}
