<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Route::get('/tasks', [TaskController::class, 'index']);
// Route::get('/tasks/{id}', [TaskController::class, 'show']);
// Route::post('/tasks', [TaskController::class, 'store']);
// Route::put('/tasks/{id}', [TaskController::class, 'update']);
// Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
// Route::post('/tasks/export', [TaskController::class, 'export']);

Route::apiResource('tasks', TaskController::class);

// Route::get('/comments', [CommentController::class, 'index']);
// Route::get('/comments/{id}', [CommentController::class, 'show']);
// Route::post('/comments', [CommentController::class, 'store']);
// Route::put('/comments/{id}', [CommentController::class, 'update']);
// Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

Route::apiResource('comments', CommentController::class);