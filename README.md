# BuildOne 公式サイト

**社長、そのシステム作れます。** — AIで会社の業務システムを1週間で開発・公開。

BuildOneは、AIを活用して企業で本当に使える業務システムを企画・設計・開発・検証し、その過程をYouTube（@buildonejp）で公開するプロジェクトです。

このリポジトリは、公開中の**公式サイト（ショールーム型LP）**であると同時に、100種類以上の業務システムを同じコードベースで高速開発する **BuildOne Framework**（Laravelベース）の基盤です。

- 本番URL: https://buildone.sakura.ne.jp
- 最優先設計基準: [docs/framework-spec-v2.md](docs/framework-spec-v2.md)

## BuildOne Framework

Feature First + Clean Architecture（Presentation → Application → Domain → Infrastructure）で、モジュール（業務システム）を規約に従って追加するだけで自動配線される。

```bash
php artisan buildone:new-module Entry   # 4層の縦割りスライスを生成
composer dump-autoload && php artisan migrate
# → /entries が有効化される（設定ファイルの手編集は不要）
```

- アーキテクチャ: [docs/architecture.md](docs/architecture.md)
- モジュール開発: [docs/module-guide.md](docs/module-guide.md) ／ CLI: [docs/generator-guide.md](docs/generator-guide.md)
- コーディング規約: [docs/coding-rule.md](docs/coding-rule.md) ／ UI: [docs/ui-guide.md](docs/ui-guide.md)

品質ゲート: `composer ci`（Pint → PHPStan level 6 → Pest）。CI は `.github/workflows/ci.yml`。

### 管理画面（初期セットアップ）

認証・ロール権限・ユーザー管理・監査ログ・ダッシュボードを備えた管理画面（`/admin`）がある。

```bash
php artisan migrate
php artisan db:seed --class='Database\Seeders\RoleSeeder'   # 既定ロール(super_admin/admin/viewer)
php artisan buildone:make-admin admin@example.com --name="運営者"   # 初期管理者。パスワードは一度だけ表示
```

`/login` からログイン。**Seeder に本番用の固定パスワードは置かず**、初期管理者は上記コマンドで作成する（パスワードはランダム生成・一度だけ表示）。権限は `App\Core\Authorization\Permission` で一元管理。

## 技術構成

| 項目 | 内容 |
|---|---|
| フレームワーク | Laravel 13（PHP 8.3） |
| フロントエンド | Blade + Tailwind CSS 4 + Vite 8 |
| データベース | 不使用（セッション・キャッシュはファイル駆動） |
| 本番サーバー | さくらのレンタルサーバー（FreeBSD / PHP 8.3.31） |
| デプロイ | rsync over SSH（WSLから `deploy/deploy.sh`） |

必要バージョン: PHP 8.3+ / Node.js 20+（開発は Node 24 で確認）/ Composer 2

## ローカル開発

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run dev          # 別ターミナルで
php artisan serve
```

## ビルド

```bash
npm run build        # public/build に成果物を出力（Git管理外）
```

## テスト

```bash
php artisan test     # Feature Test 14件
./vendor/bin/pint    # コードフォーマット
```

## 環境変数（BuildOne固有）

| 変数 | 効果 |
|---|---|
| `BUILDONE_YOUTUBE_URL` | 設定すると「YouTubeを見る」ボタン等がチャンネルへリンクする。未設定なら「チャンネル準備中」表示 |
| `BUILDONE_YOUTUBE_VIDEO_URL` | 設定するとトップに動画を埋め込む。未設定なら「動画は近日公開」表示 |
| `CONTACT_URL` | 問い合わせフォームのURL。設定すると問い合わせボタンが有効化 |
| `CONTACT_EMAIL` | 問い合わせメールアドレス。`CONTACT_URL` 未設定時に使われる |

本番で変更する場合はサーバーの `~/buildone-web/.env` を編集し、`php artisan config:cache` を実行する。

コンテンツ（システム一覧・特徴・開発の流れ）は `config/buildone.php` の配列で管理。詳細は [docs/content-guide.md](docs/content-guide.md)。

## デプロイ

```bash
# WSL (Ubuntu) から実行
cd /mnt/c/dev/buildone-web
bash deploy/deploy.sh          # 通常デプロイ
bash deploy/deploy.sh --first  # 初回のみ（メンテナンスモードを使わない）
```

デプロイ後、ローカルで開発を続けるときは `composer install` で dev 依存を戻すこと。
手順の詳細・サーバー構成は [docs/deployment.md](docs/deployment.md)。

## ロールバック

このサイトは静的コンテンツ中心のため、**直前のコミットに戻して再デプロイ**するのが基本手順:

```bash
git log --oneline            # 戻したいコミットを確認
git checkout <commit> -- .   # または git revert
bash deploy/deploy.sh
```

サーバー側だけの緊急停止はメンテナンスモードで行う:

```bash
ssh buildone-sakura "cd ~/buildone-web && php artisan down"
ssh buildone-sakura "cd ~/buildone-web && php artisan up"
```

## ドキュメント

- [docs/framework-spec-v2.md](docs/framework-spec-v2.md) — **最優先**: Framework v2.0 設計書
- [docs/architecture.md](docs/architecture.md) — アーキテクチャ（Feature First + Clean Architecture）
- [docs/coding-rule.md](docs/coding-rule.md) — コーディング規約・品質ゲート
- [docs/module-guide.md](docs/module-guide.md) — モジュール開発ガイド
- [docs/generator-guide.md](docs/generator-guide.md) — ジェネレータ / BuildOne CLI
- [docs/ui-guide.md](docs/ui-guide.md) — UI・ブランドカラー
- [docs/deployment.md](docs/deployment.md) — さくらのサーバーへのデプロイ手順
- [docs/server-audit.md](docs/server-audit.md) — サーバー環境の調査結果
- [docs/content-guide.md](docs/content-guide.md) — LPコンテンツの追加・変更ガイド
