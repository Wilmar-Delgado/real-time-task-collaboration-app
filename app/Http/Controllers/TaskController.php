<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index() {
        return response()->json(Task::all());
    }

    public function show($id) {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'created_by'  => 'required|exists:users,id',
            'title'       => 'required|string',
            'description' => 'required|string',
            'status'      => 'required|in:open,in_progress,completed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $task = new Task();
        // $task->created_by = $request->created_by;
        // $task->title = $request->title;
        // $task->description = $request->description;
        // $task->status = $request->status;
        // $task->save();
        $task = Task::create($validator->validated());

        return response()->json([
            'message' => 'Task created sucessfully',
            'task'    => $task
        ], 201);
    }

    public function update(Request $request, $id) {
        $task = Task::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string',
            'description' => 'required|string',
            'status'      => 'required|in:open,in_progress,completed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $task->title = $request->title;
        // $task->description = $request->description;
        // $task->status = $request->status;
        // $task->save();
        $task->update($validator->validated());

        return response()->json([
            'message' => 'Task updated successfully',
            'task'    => $task
        ], 200);
    }

    public function destroy($id) {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    } 
}
