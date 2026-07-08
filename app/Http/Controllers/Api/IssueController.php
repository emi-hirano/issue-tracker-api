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
        return Issue::with(['reporter', 'assignee', 'project', 'labels'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    // 読み込み
    public function show($id)
    {
        return Issue::with([
            'reporter',
            'assignee',
            'project',
            'labels',
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'comments.user',
        ])->findOrFail($id);
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
            // ラベルidの配列。任意。各idはlabelsテーブルに存在すること
            'label_ids'   => 'nullable|array',
            'label_ids.*' => 'exists:labels,id',
        ]);

        // ラベルidは課題本体のカラムではないので、いったん取り出して除外する
        $labelIds = $validated['label_ids'] ?? [];
        unset($validated['label_ids']);

        // 課題本体を作成
        $issue = Issue::create($validated);

        // 中間テーブルにラベルを紐付ける
        $issue->labels()->attach($labelIds);

        // 紐付けたラベルも含めて返す
        return $issue->load('labels');
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
            'label_ids'   => 'nullable|array',
            'label_ids.*' => 'exists:labels,id',
        ]);

        // ラベルidを取り出して本体データから除外
        $labelIds = $validated['label_ids'] ?? [];
        unset($validated['label_ids']);

        // 課題本体を更新
        $issue = Issue::findOrFail($id);
        $issue->update($validated);

        // ラベルを付け替える（sync：送られたidだけにする）
        $issue->labels()->sync($labelIds);

        return $issue->load('labels');
    }

    // 削除
    public function destroy($id)
    {
        $issue = Issue::findOrFail($id);
        $issue->delete();
        return response()->json(['message' => '削除しました']);
    }
}