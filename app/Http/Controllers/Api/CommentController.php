<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // その課題のコメント一覧
    public function index($issueId)
    {
        $issue = Issue::findOrFail($issueId);
        return $issue->comments()->with('user')->get();
    }

    // その課題にコメントを追加
    public function store(Request $request, $issueId)
    {
        $issue = Issue::findOrFail($issueId);

        // 本文だけ受け取る（投稿者はフロントから受け取らない）
        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        // 投稿者はログイン中のユーザーに固定（なりすまし防止）
        $comment = $issue->comments()->create([
            'body'    => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        return $comment->load('user'); // 投稿者情報も付けて返す
    }

    // コメント削除
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->json(['message' => '削除しました']);
    }
}