# プロンプト: デプロイ

BuildOne をさくらのレンタルサーバーへ安全にデプロイしてください。

## 前提（必読）

- `docs/deployment.md`（手順・案B構成）、`docs/server-environment.md`、`docs/security.md`。
- SSH: `buildone-sakura`。秘密鍵の内容・場所を表示しない。
- 本番: `APP_ENV=production` / `APP_DEBUG=false` / `APP_URL=https://buildone.sakura.ne.jp`。
- 本番 `.env` はサーバー側にのみ存在し、rsync で上書きしない。

## 安全ルール

- いきなり削除しない。`rm -rf` を使わない。上書き前に一覧と内容を確認。
- 初回は `rsync --delete` を使わない。既存ファイルがあれば容量確認のうえ `~/backups/buildone-YYYYMMDD-HHMMSS` にバックアップ。
- VPS用のroot操作・OSパッケージ管理・Apache再設定をしない。sudoが無くても異常と判断しない。
- GitHubへのpush・本番反映など不可逆操作は、対象と差分を確認してから。ユーザーの許可を得る。

## 手順

1. ローカルで `composer install --no-dev --optimize-autoloader` と `npm run build`。
2. `bash deploy/deploy.sh`（通常）／ `--first`（初回）。WSLから実行。
3. サーバーで `optimize:clear → config:cache → route:cache → view:cache`。DBを使う構成なら `migrate --force`。
4. 公開後: `curl -I https://buildone.sakura.ne.jp/`（200+HTTPS）、`.env`/`composer.json`/`storage`/`.git` が 403/404、Laravelログにエラーが無いことを確認。

## 完了条件

HTTPSで正常表示、機密ファイル非公開、`APP_DEBUG=false`、ログ正常。結果を報告する。
