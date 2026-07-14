# BuildOne 公式サイト

**社長、そのシステム作れます。** — AIで会社の業務システムを1週間で開発・公開。

BuildOneは、AIを活用して企業で本当に使える業務システムを企画・設計・開発・検証し、その過程をYouTube（@buildonejp）で公開するプロジェクトです。このリポジトリはその公式サイト（ショールーム型LP）です。

- 本番URL: https://buildone.sakura.ne.jp

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
ssh buildone "cd ~/buildone-web && php artisan down"
ssh buildone "cd ~/buildone-web && php artisan up"
```

## ドキュメント

- [docs/deployment.md](docs/deployment.md) — さくらのサーバーへのデプロイ手順
- [docs/server-audit.md](docs/server-audit.md) — サーバー環境の調査結果
- [docs/content-guide.md](docs/content-guide.md) — コンテンツの追加・変更ガイド
