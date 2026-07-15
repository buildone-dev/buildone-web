# サーバー環境（さくらのレンタルサーバー）

> 生の調査ログは `docs/server-audit.md`。本書はフレームワーク運用の観点でまとめたもの。
> 秘密情報（ホスト名・ユーザー名・IP・鍵）は記録しない。

## 種別・接続

- **さくらのレンタルサーバー**（共有ホスティング。VPSではない）。OS: FreeBSD、ログインシェル: **csh**（`2>/dev/null` 等の bash 構文は使えない）。
- SSH: エイリアス `buildone-sakura`（別名 `buildone`）。`~/.ssh/config` で鍵を解決（内容は非表示）。`ssh buildone-sakura` で接続。

## ツールチェーン

| ツール | サーバー | 対応 |
|---|---|---|
| PHP | 8.3 に設定可（`~/.php.version`／コントロールパネル）。Laravel必須拡張は全て有 | 8.3 を使用 |
| Composer | **無し** | ローカルで `composer install --no-dev` して vendor を rsync |
| Node.js / npm | **無し** | ローカルで `npm run build` して `public/build` を rsync |
| Git | あり（2.34） | デプロイは rsync を採用 |
| rsync | あり | デプロイに使用 |

## DB / 実行環境

- **MySQL 利用可**（コントロールパネルで作成）。初期は SQLite 許容だが、共有サーバーでは MySQL 推奨。
- cron: コントロールパネルから設定可。queue のワーカー常駐は共有サーバーでは非推奨（cron + `queue:work --stop-when-empty` 等で代替）。
- symlink（`storage:link`）: 可。
- `storage` / `bootstrap/cache` は書き込み可（共有サーバーのため 777 は避け、所有者に書込権限を付与）。

## 配置・公開（案B）

- Laravel本体: `~/buildone-web`（Web非公開）。公開: `~/www`（`public` の中身＋パス書換 `index.php`）。詳細 `docs/deployment.md`。
- 初回デプロイは `rsync --delete` を使わない。
- 本番URL: `https://buildone.sakura.ne.jp`（無料SSL有効・HTTPS確認済み）。

## フレームワーク導入時の注意

- 認証・監査ログ等でDBが必要になったら MySQL を作成し、本番 `.env` を更新して `php artisan migrate --force`。現状の公開LPはDBレスのため、DB導入は Core（認証）着手時に行う。
