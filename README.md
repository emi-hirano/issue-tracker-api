# Issue Tracker API

Issue Tracker のバックエンド API です。

React + TypeScript で構築したフロントエンドと連携し、課題（Issue）の管理機能を提供します。

**Frontend Repository**

フロントエンドの起動方法やテストアカウントについては、以下のリポジトリをご覧ください。
- https://github.com/emi-hirano/issue-tracker-front

---

## 概要

Laravel を使用して REST API を構築しました。

認証には Laravel Sanctum を採用し、Issue・Project・Label・Comment の CRUD API を実装しています。

また、検索、ページネーション、ログインユーザーに割り当てられた課題一覧（My Issues）など、実務を意識した機能を実装しています。

---

## 開発方針

このプロジェクトでは、生成AI（ChatGPT・Claude）を積極的に活用して開発を行いました。

AIをコード生成ツールとして利用するだけではなく、設計の相談、実装方針の比較、デバッグ、リファクタリングなどにも活用しています。

生成されたコードはそのまま利用するのではなく、内容を理解・検証し、自分で説明できるコードのみを採用することを開発方針としました。

また、実際の業務で利用される課題管理システムを意識し、機能追加や改善を繰り返しながら完成度を高めました。

---

## 使用技術

| 項目 | 技術 |
|------|------|
| Framework | Laravel 13 |
| Language | PHP 8.4 |
| Database | MySQL 8 |
| Authentication | Laravel Sanctum |
| ORM | Eloquent ORM |
| Development Environment | Laravel Sail (Docker) |
| API | REST API |

---

## 主な機能

- Issue（課題）のCRUD
- Project（プロジェクト）のCRUD
- Label（ラベル）のCRUD
- Comment（コメント）の投稿・削除
- Laravel Sanctumによる認証
- タイトル・ステータス・優先度・ラベルによる複合検索
- ページネーション
- My Issues（ログインユーザーにアサインされた課題一覧API）

---

## 工夫した点

- REST APIとして設計し、フロントエンドとの責務を分離
- Laravel Sanctumによるトークン認証を実装
- タイトル検索では特殊文字をエスケープし、安全な部分一致検索を実装
- ラベル検索では多対多リレーション（whereHas）を利用
- Eager Loadingを利用し、N+1問題を回避
- ページネーションを導入し、大量データでも快適に利用できるよう改善
- ログインユーザーにアサインされた課題のみ取得する My Issues API を実装

---

## セットアップ

```bash
git clone <repository-url>

cd issue-tracker-api

cp .env.example .env

composer install

./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate

./vendor/bin/sail artisan migrate --seed
```

API

```
http://localhost
```

---

## 今後の改善予定

- 権限管理の強化
- Feature Test・Unit Testの拡充
- APIレスポンスの統一
- OpenAPI（Swagger）によるAPI仕様書の作成
- AIを活用したIssue要約・タイトル提案機能
