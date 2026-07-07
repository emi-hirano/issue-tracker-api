<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IssueController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\LabelController; 
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/issues', [IssueController::class, 'index']);
Route::get('/issues/{id}', [IssueController::class, 'show']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);
Route::get('/labels', [LabelController::class, 'index']);
Route::get('/labels/{id}', [LabelController::class, 'show']);
Route::get('/users', [UserController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/issues', [IssueController::class, 'store']);
    Route::put('/issues/{id}', [IssueController::class, 'update']);
    Route::delete('/issues/{id}', [IssueController::class, 'destroy']);

    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    Route::post('/labels', [LabelController::class, 'store']);
    Route::put('/labels/{id}', [LabelController::class, 'update']);
    Route::delete('/labels/{id}', [LabelController::class, 'destroy']);

    Route::get('/issues/{issue}/comments', [CommentController::class, 'index']);
    Route::post('/issues/{issue}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});