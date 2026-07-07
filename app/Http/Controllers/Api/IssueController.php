<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    // 読み込み
    public function index()
    {
        return Issue::with(['reporter', 'assignee', 'project', 'labels'])->get();
    }
    
    // 読み込み（１件）
    public function show($id)
    {
        return Issue::findOrFail($id);
    }

    // 追加
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'reporter_id' => 'required|exists:users,id',
            'assignee_id' => 'nullable|exists:users,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:open,in_progress,resolved,closed',
            'priority'    => 'required|in:low,medium,high',
        ]);

        $issue = Issue::create($validated);
        return $issue;
    }

    // 更新
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'reporter_id' => 'required|exists:users,id',
            'assignee_id' => 'nullable|exists:users,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:open,in_progress,resolved,closed',
            'priority'    => 'required|in:low,medium,high',
        ]);

        $issue = Issue::findOrFail($id);
        $issue->update($validated);
        return $issue;
    }

    // 削除
    public function destroy($id)
    {
        $issue = Issue::findOrFail($id);
        $issue->delete();
        return response()->json(['message' => '削除しました']);
    }
}