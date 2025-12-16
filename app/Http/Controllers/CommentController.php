<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\CommentService;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Resources\CommentResource;
use App\Http\Requests\CommentUpdateRequest;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index() 
    {
        // return response()->json(Comment::all());
        return CommentResource::collection(Comment::paginate(10));
    }

    public function show($id) 
    {
        $comment = Comment::findOrFail($id);
        // return response()->json($comment);
        return new CommentResource($comment);
    }

    public function store(TaskStoreRequest $request) 
    {
        // Validation logic can be added here if not using Form Requests
        
        $comment = $this->commentService->createComment($request->validated());

        return response()->json([
            'message' => 'Comment created sucessfully',
            'comment' => new CommentResource($comment)
        ], 201);
    }

    public function update(CommentUpdateRequest $request, $id) 
    {
        $comment = Comment::findOrFail($id);

        // Validation logic can be added here if not using Form Requests

        $comment = $this->commentService->updateComment($comment, $request->validated());

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => new CommentResource($comment)
        ], 200);
    }

    public function destroy($id) 
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    } 
}
