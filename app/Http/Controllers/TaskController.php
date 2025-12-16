<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Jobs\ExportTaskJob;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Resources\TaskResource;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request) 
    {
        $query = Task::query();

        if (!empty($request->created_by)) {
            $query->where('created_by', $request->created_by);
        }

        //with() is used to eager load relationships before the main query is executed
        $tasks = $query->with('comments')->get();

        return response()->json(TaskResource::collection($tasks));
    }

    public function show($id) {
        $task = Task::findOrFail($id);
        // return response()->json($task);
        return new TaskResource($task);
    }

    public function store(TaskStoreRequest $request) 
    {
        $task = $this->taskService->createTask($request->validated());

        // Use load() to get the 'user' data efficiently *after* creation
        $task->load('creator'); 

        return response()->json([
            'message' => 'Task created sucessfully',
            'task'    => new TaskResource($task)
        ], 201);
    }

    public function update(TaskUpdateRequest $request, $id) 
    {
        $task = Task::findOrFail($id);

        $task = $this->taskService->updateTask($task, $request->validated());

        return response()->json([
            'message' => 'Task updated successfully',
            'task'    => new TaskResource($task)
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
