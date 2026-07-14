# デプロイ手順（さくらのレンタルサーバー）

> **注意**: このドキュメントに認証情報・秘密鍵・APP_KEYを書かないこと。

## サーバー構成（案B: 公開フォルダ分離方式）

さくらのレンタルサーバーの公開ディレクトリは `~/www`。Laravel本体をWeb公開領域の外に置く。

```
/home/buildone/
├── buildone-web/        ← Laravel本体（Webから到達不可）
│   ├── app/ bootstrap/ config/ resources/ routes/ vendor/ ...
│   └── .env             ← 本番設定（サーバーにのみ存在。デプロイで上書きされない）
└── www/                 ← 公開ディレクトリ（docroot）
    ├── index.php        ← deploy/index.sakura.php（../buildone-web を参照するよう変更済み）
    ├── .htaccess        ← deploy/htaccess.sakura（HTTPS強制 + セキュリティヘッダー）
    ├── build/           ← Viteビルド成果物（ローカルでビルドして転送）
    ├── robots.txt / sitemap.xml / favicon.svg / ogp.svg
    └── （その他 public/ の中身）
```

- `index.php` 変更の理由: さくらの初期ドメインは docroot が `~/www` のため、標準の `public/` 構成をそのまま使えない。`index.php` の参照先を `../buildone-web` に変え、`$app->usePublicPath(__DIR__)` で公開パスを `~/www` に設定している（差分は `deploy/index.sakura.php` のコメント参照）。
- 代替案（案A）: コントロールパネルで公開フォルダを変更できる場合は、Laravel一式を `~/www/buildone` に置き公開フォルダを `buildone/public` にする構成も可能。その場合は標準の `public/index.php` がそのまま使え、`deploy/deploy.sh` の `APP_REMOTE`/`WEB_REMOTE` と転送内容の変更が必要。

## 前提（デプロイ元マシン）

- Windows + WSL (Ubuntu)。rsync は WSL 側のものを使う
- WSL の `~/.ssh/config` に `Host buildone` が設定済み（鍵は `~/.ssh/buildone_sakura`、パーミッション600）
- Windows 側に PHP 8.3 / Composer / Node.js（ビルドは cmd.exe 経由で実行される）

## 通常デプロイ

```bash
# WSLから
cd /mnt/c/dev/buildone-web
bash deploy/deploy.sh
```

スクリプトが行うこと:

1. `composer install --no-dev --optimize-autoloader` + `npm run build`（ローカル）
2. `php artisan down`（メンテナンスモード）
3. アプリ本体を `~/buildone-web/` へ rsync（`--delete` あり。`.env`・`storage` の実行時ファイル・`bootstrap/cache` は除外＝保護）
4. `public/` の中身を `~/www/` へ rsync（`--delete` なし）+ さくら用 `index.php` / `.htaccess` を配置
5. サーバーで `optimize:clear` → `config:cache` → `route:cache` → `view:cache`
6. `php artisan up` → HTTPS応答確認

デプロイ後、ローカルの dev 依存を戻す: `composer install`

## 初回セットアップ（実施済み・記録）

1. サーバーの PHP を 8.3 に設定（`~/.php.version` に `8.3`。コントロールパネルの「PHPのバージョン選択」でも可）
2. 本番 `.env` を `~/buildone-web/.env` に作成（`APP_ENV=production` / `APP_DEBUG=false` / `APP_URL=https://buildone.sakura.ne.jp`、権限600）
3. `bash deploy/deploy.sh --first`
4. `ssh buildone "cd ~/buildone-web && php artisan key:generate --force && php artisan config:cache"`

## デプロイ後の確認

```bash
curl -I https://buildone.sakura.ne.jp/          # 200 + セキュリティヘッダー
curl -I http://buildone.sakura.ne.jp/           # 302 → https
curl -o /dev/null -w "%{http_code}\n" https://buildone.sakura.ne.jp/.env        # 403
curl -o /dev/null -w "%{http_code}\n" https://buildone.sakura.ne.jp/composer.json  # 404
ssh buildone "tail ~/buildone-web/storage/logs/laravel-$(date +%F).log"         # エラーが無いこと
```

## トラブルシューティング

| 症状 | 原因と対処 |
|---|---|
| 500 + ログに "No application encryption key" | `.env` の `APP_KEY` が空。`php artisan key:generate --force && php artisan config:cache` |
| 設定変更が反映されない | config がキャッシュされている。`php artisan config:cache` を再実行 |
| CSS/JSが404 | `~/www/build/` が古い。ローカルで `npm run build` して再デプロイ |
| rsync が無反応でハングする | WSL の known_hosts にホスト鍵が無い。`ssh buildone whoami` を一度手動実行して鍵を受け入れる |
| サーバーのシェルは csh | `2>/dev/null` 等の bash 構文は使えない点に注意 |
