<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Issue;

class IssueApiTest extends TestCase
{
    // 各テストごとにDBをまっさらに戻す
    use RefreshDatabase;

    /**
     * 未認証では課題一覧を取得できないこと。
     * 参照系ルートに認証が掛かっていない不具合があったため、その再発防止として追加。
     */
    public function test_未認証では課題一覧を取得できない(): void
    {
        $response = $this->getJson('/api/issues');

        $response->assertStatus(401);
    }

    /**
     * 認証済みユーザーは課題一覧を取得できること。
     * 認証を掛けすぎて正常系まで壊していないことを確認する。
     */
    public function test_認証済みなら課題一覧を取得できる(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/issues');

        $response->assertStatus(200);
    }

    /**
     * 未認証では課題を作成できないこと。
     */
    public function test_未認証では課題を作成できない(): void
    {
        $response = $this->postJson('/api/issues', [
            'project_id' => 1,
            'title' => 'テスト課題',
        ]);

        $response->assertStatus(401);
    }

    /**
     * 未認証では課題詳細を取得できないこと。
     */
    public function test_未認証では課題詳細を取得できない(): void
    {
        $response = $this->getJson('/api/issues/1');

        $response->assertStatus(401);
    }

    /**
     * 未認証ではユーザー一覧を取得できないこと。
     * 課題のリレーション経由でユーザー情報が漏れないことを確認する。
     */
    public function test_未認証ではユーザー一覧を取得できない(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401);
    }

    /**
     * 認証済みユーザーは課題を作成できること。
     */
    public function test_認証済みなら課題を作成できる(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/issues', [
            'project_id' => $project->id,
            'reporter_id' => $user->id,
            'title' => 'テスト課題',
            'description' => 'テスト用の説明',
            'status' => 'open',
            'priority' => 'medium',
        ]);

        $response->assertStatus(201);

        // DBに実際に保存されたか確認する
        $this->assertDatabaseHas('issues', [
            'title' => 'テスト課題',
        ]);
    }

    /**
     * タイトルが空の場合は課題を作成できないこと。
     */
    public function test_タイトルが空だと課題を作成できない(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/issues', [
            'project_id' => $project->id,
            'reporter_id' => $user->id,
            'title' => '', // 空にする
            'status' => 'open',
            'priority' => 'medium',
        ]);

        $response->assertStatus(422); // バリデーションエラー
        $response->assertJsonValidationErrors('title');
    }

    /**
     * コメントの投稿者はリクエストの値ではなく、認証トークンのユーザーで決まること。
     * 他人のIDを送り込んでも、なりすましができないことを確認する。
     */
    public function test_コメントの投稿者は他人になりすませない(): void
    {
        $user = User::factory()->create();      // ログインする人
        $otherUser = User::factory()->create(); // なりすまし先の他人
        $issue = Issue::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/issues/{$issue->id}/comments", [
            'body' => 'なりすましを試みるコメント',
            'user_id' => $otherUser->id, // 他人のIDを送り込む
        ]);

        $response->assertStatus(201);

        // 送り込んだ他人のIDではなく、ログイン中のユーザーで保存されること
        $this->assertDatabaseHas('comments', [
            'body' => 'なりすましを試みるコメント',
            'user_id' => $user->id,
        ]);
    }

    /**
     * キーワード検索でLIKEのメタ文字（%）を送っても、全件返らないこと。
     * エスケープ処理が効いていることを確認する。
     */
    public function test_キーワードにパーセント記号を含めても全件は返らない(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // タイトルに % を含まない課題を3件用意する
        Issue::factory()->count(3)->create(['title' => '通常の課題']);

        $response = $this->getJson('/api/issues?keyword=%');

        $response->assertStatus(200);

        // エスケープされていれば、% を含むタイトルは無いので0件になる
        $response->assertJsonCount(0, 'data');
    }
}