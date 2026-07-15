# BuildOne 開発ルールブック（CLAUDE.md）

> **最優先の設計基準**: `docs/framework-spec-v2.md`（BuildOne Framework v2.0 設計書）。
> 本プロジェクトは単なるLaravelアプリではなく、100種類以上の業務システムを同じコードベースで
> 高速開発する **BuildOne Framework** である。設計・実装は下記アーキテクチャに従うこと。
>
> - アーキテクチャ: [docs/architecture.md](docs/architecture.md)（Feature First + Clean Architecture）
> - コーディング規約: [docs/coding-rule.md](docs/coding-rule.md)
> - モジュール開発: [docs/module-guide.md](docs/module-guide.md)
> - ジェネレータ/CLI: [docs/generator-guide.md](docs/generator-guide.md)
> - UI: [docs/ui-guide.md](docs/ui-guide.md)
> - デプロイ: [docs/deployment.md](docs/deployment.md)
>
> **必ず品質ゲートを通す**: `composer ci`（Pint → PHPStan lv6 → Pest）。
> モジュール追加は `php artisan buildone:new-module {Name}`。設定ファイルの手編集は不要。

## プロジェクト概要

- ブランド名: BuildOne
- キャッチコピー: 社長、そのシステム作れます。
- YouTube: BuildOne | 社長、そのシステム作れます。
- ハンドル: @buildonejp
- 初期ドメイン: https://buildone.sakura.ne.jp

## コンセプト

AIを活用して会社で本当に使える業務システムを1週間で企画・設計・開発・検証し、その過程をYouTubeで公開する。

## ブランド方針

- 現場で使えることを最優先
- AIは手段
- 成功も失敗も公開
- YouTube・GitHub・Webサイトをブランド資産として育てる

## 技術スタック

- Laravel
- PHP 8.3+
- Blade
- Tailwind CSS
- Vite
- SQLite（初期）
- MySQL（必要時）
- Git / GitHub

## UI

- Apple・Stripe・Linear風のシンプルなBtoBデザイン
- ブランドカラー
  - `#2563EB`（メイン）
  - `#0F172A`（サブ）
  - `#22C55E`（アクセント）

## コーディング

- 可読性重視
- Fat Controller禁止
- Serviceクラス活用
- FormRequest利用
- .envはコミット禁止
- 本番はAPP_DEBUG=false

## Git

- ブランチ: main / develop / feature/*
- コミット: feat, fix, docs, refactor, chore

## デプロイ

- さくらレンタルサーバー
- buildone.sakura.ne.jp
- SSH Host: buildone-sakura
- 手順: `docs/deployment.md`（WSLから `bash deploy/deploy.sh`）

## YouTube

シリーズ: AIシステム1週間チャレンジ

動画構成: 1. 完成 2. 課題 3. 開発 4. 失敗 5. 改善 6. 実演 7. まとめ 8. CTA

## システム命名

- BuildOne Entry
- BuildOne Time
- BuildOne Shift
- BuildOne Reserve
- BuildOne Report

## Claude Codeルール

- 環境調査から開始
- 危険操作禁止
- テスト実施
- README・docs更新
- 推測実装しない

## ディレクトリ

```
buildone-web/
├── app/
├── resources/
├── routes/
├── docs/
├── prompts/
├── README.md
└── CLAUDE.md
```

## ミッション

社長、そのシステム作れます。
AIを使って本当に役立つ業務システムを作り続ける。
