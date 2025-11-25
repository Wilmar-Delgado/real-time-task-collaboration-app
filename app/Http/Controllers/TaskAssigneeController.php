<?php

namespace App\Http\Controllers;

use App\Models\TaskAssignee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskAssigneeController extends Controller
{
    public function index()
    {
        return response()->json(TaskAssignee::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Avoid duplicates
        $existing = TaskAssignee::where('user_id', $request->user_id)
                                ->where('task_id', $request->task_id)
                                ->first();

        if ($existing) {
            return response()->json(['message' => 'User already assigned'], 409);
        }

        $data = TaskAssignee::create($validator->validated());

        return response()->json([
            'message' => 'User assigned successfully',
            'data'    => $data,
        ], 201);
    }

    public function destroy($id)
    {
        $taskAssignee = TaskAssignee::findOrFail($id);
        $taskAssignee->delete();

        return response()->json(['message' => 'Assignment removed successfully']);
    }
}