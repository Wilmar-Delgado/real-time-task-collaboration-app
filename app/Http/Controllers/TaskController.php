<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Jobs\ExportTaskJob;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index() 
    {
        return response()->json(Task::all());
    }

    public function show($id) {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function store(TaskStoreRequest $request) 
    {
        // Validation logic can be added here if not using Form Requests
        
        $task = $this->taskService->createTask($request->validated());

        return response()->json([
            'message' => 'Task created sucessfully',
            'task'    => $task
        ], 201);
    }

    public function update(TaskUpdateRequest $request, $id) 
    {
        $task = Task::findOrFail($id);

        // Validation logic can be added here if not using Form Requests

        $task = $this->taskService->updateTask($task, $request->validated());

        return response()->json([
            'message' => 'Task updated successfully',
            'task'    => $task
        ], 200);
    }

    public function destroy($id) 
    {
        $task = Task::findOrFail($id);
        $this->taskService->deleteTask($task);

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function export() 
    {
        // Dispatch the job to export tasks
        ExportTaskJob::dispatch();

        return response()->json(['message' => 'Task export has been initiated. You will be notified once it is complete.']);
    }
}
