<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index() {
        return response()->json(Comment::all());
    }

    public function show($id) {
        $comment = Comment::findOrFail($id);
        return response()->json($comment);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
            'body'    => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(), 422]);
        }

        // $comment = new Comment();
        // $comment->user_id = $request->user_id;
        // $comment->task_id = $request->task_id;
        // $comment->body = $request->body;
        // $comment->save();
         $comment = Comment::create($validator->validated());

        return response()->json([
            'message' => 'Comment created sucessfully',
            'comment' => $comment
        ], 201);
    }

    public function update(Request $request, $id) {
        $comment = Comment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
            'body'    => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(), 422]);
        }

        // $comment->user_id = $request->user_id;
        // $comment->task_id = $request->task_id;
        // $comment->body = $request->body;
        // $comment->save();
        $comment->update($validator->validated());

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment
        ], 200);
    }

    public function destroy($id) {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    } 
}
