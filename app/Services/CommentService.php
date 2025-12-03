<?php

namespace App\Services;

use App\Models\Comment;

class CommentService
{
    public function createComment(array $data)
    {
        return Comment::create($data);
    }

    public function updateComment(Comment $comment, array $data)
    {
        $comment->update($data);
        return $comment;
    }

    public function deleteComment(Comment $comment): void
    {
        $comment->delete();
    }
}