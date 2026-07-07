<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. ユーザーを5人作る
        $users = \App\Models\User::factory()->count(5)->create();

        // 2. ラベルを14種類作る（実務を想定したラベルセット）
        $labelData = [
            ['name' => 'バグ', 'color' => '#EF4444'],
            ['name' => '新機能', 'color' => '#3B82F6'],
            ['name' => '改善', 'color' => '#10B981'],
            ['name' => 'ドキュメント', 'color' => '#8B5CF6'],
            ['name' => 'UI', 'color' => '#F59E0B'],
            ['name' => 'バックエンド', 'color' => '#06B6D4'],
            ['name' => 'フロントエンド', 'color' => '#EC4899'],
            ['name' => 'データベース', 'color' => '#64748B'],
            ['name' => 'API', 'color' => '#0EA5E9'],
            ['name' => 'セキュリティ', 'color' => '#DC2626'],
            ['name' => 'パフォーマンス', 'color' => '#F97316'],
            ['name' => 'テスト', 'color' => '#14B8A6'],
            ['name' => '優先度高', 'color' => '#B91C1C'],
            ['name' => '保留', 'color' => '#6B7280'],
        ];

        $labels = collect($labelData)->map(function ($data) {
            return \App\Models\Label::create($data);
        });

        // 3. プロジェクトを3つ作る
        $projects = \App\Models\Project::factory()->count(3)->create();

        // 4. 各プロジェクトに課題を5件ずつ作る
        $projects->each(function ($project) use ($users, $labels) {
            \App\Models\Issue::factory()->count(5)->create([
                'project_id'  => $project->id,
                'reporter_id' => $users->random()->id,
                'assignee_id' => fake()->boolean(60) ? $users->random()->id : null,
            ])->each(function ($issue) use ($labels) {
                // 各課題にランダムで1〜3個のラベルを付ける
                $issue->labels()->attach(
                    $labels->random(rand(1, 3))->pluck('id')->toArray()
                );
                // 各課題にコメントを2件
                \App\Models\Comment::factory()->count(2)->create([
                    'issue_id' => $issue->id,
                ]);
            });
        });
    }
}
