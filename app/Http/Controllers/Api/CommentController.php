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

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'body'    => 'required|string',
        ]);

        $comment = $issue->comments()->create($validated);
        return $comment;
    }

    // コメント削除
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->json(['message' => '削除しました']);
    }
}