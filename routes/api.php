<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IssueController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| 認証不要のルート
|--------------------------------------------------------------------------
| ログインはトークンを取得する前に呼ぶ必要があるため、認証の外に置く。
*/
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| 認証が必要なルート
|--------------------------------------------------------------------------
| 参照・更新を問わず、課題データはすべてログイン済みユーザーのみに公開する。
| 個別に middleware を書くと付け忘れが起きるため、グループでまとめて指定する。
*/
Route::middleware('auth:sanctum')->group(function () {

    // ログイン中のユーザー情報
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 参照系
    Route::get('/issues', [IssueController::class, 'index']);
    // 固定URLは可変ルート（/issues/{id}）より先に定義する
    Route::get('/my-issues', [IssueController::class, 'my']); // ログインユーザーにアサインされたIssue一覧
    Route::get('/issues/{id}', [IssueController::class, 'show']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::get('/labels', [LabelController::class, 'index']);
    Route::get('/labels/{id}', [LabelController::class, 'show']);
    Route::get('/users', [UserController::class, 'index']);

    // 更新系
    Route::post('/issues', [IssueController::class, 'store']);
    Route::put('/issues/{id}', [IssueController::class, 'update']);
    Route::delete('/issues/{id}', [IssueController::class, 'destroy']);

    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    Route::post('/labels', [LabelController::class, 'store']);
    Route::put('/labels/{id}', [LabelController::class, 'update']);
    Route::delete('/labels/{id}', [LabelController::class, 'destroy']);

    // コメント
    Route::get('/issues/{issue}/comments', [CommentController::class, 'index']);
    Route::post('/issues/{issue}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});