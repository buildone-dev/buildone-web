# サーバー監査結果（2026-07-15 実施）

対象: さくらのレンタルサーバー（BuildOne 用・新規契約分）
※ 本ドキュメントには認証情報・秘密鍵・IPアドレスを記録しない。

## 環境判定

| 項目 | 結果 |
|---|---|
| 種別 | **さくらのレンタルサーバー**（共有ホスティング。VPSではない） |
| OS | FreeBSD 13.0-RELEASE-p14 (amd64) |
| ログインシェル | /bin/csh（`2>` などのbash構文は使えない点に注意） |
| ホームディレクトリ | /home/buildone |
| sudo | 不可（共有サーバーのため正常） |

## ツールチェーン

| ツール | サーバー | 対応方針 |
|---|---|---|
| PHP (CLI) | 8.2.31 NTS + OPcache | `.php.version` = `8.2`。composer.json の `config.platform.php` を `8.2.31` にピン留め |
| Composer | **無し** | ローカルで `composer install --no-dev` して vendor/ を rsync 転送 |
| Node.js / npm | **無し** | ローカルで `npm run build` して public/build を rsync 転送 |
| git | 2.34.1 | あり（今回は rsync デプロイを採用） |
| rsync | /usr/local/bin/rsync | **デプロイに使用** |

## PHP拡張（Laravel 12 必須分はすべて有効）

bcmath, ctype, curl, dom, exif, fileinfo, filter, gd, hash, iconv, intl, json,
libxml, mbstring, openssl, PDO, pdo_mysql, pdo_sqlite, session, SimpleXML,
sqlite3, tokenizer, xml, zip, zlib, imagick, apcu ほか

## 公開ディレクトリ

- ドキュメントルート: `/home/buildone/www`（監査時点で**完全に空** → バックアップ不要）
- ディスク: /home 3.9T 中 2.6T 空き（26% 使用）

## HTTP/HTTPS の現状（デプロイ前）

- `http://buildone.sakura.ne.jp/` → 302
- `https://buildone.sakura.ne.jp/` → 403（www が空のため）。**証明書は有効**（curl がエラーなく応答）→ HTTPS 利用可能

## デプロイ方式の決定

- 案A（第一候補）: Laravel 本体を `~/www/buildone/` に置き、コントロールパネルで公開フォルダを `buildone/public` に変更
- 案B（フォールバック）: 公開フォルダを変更できない場合、Laravel 本体を `~/buildone-web/`（www の外）に置き、`~/www/` に public の中身のみ配置して index.php のパスを修正
- コントロールパネルで公開フォルダ変更が可能かはシェルからは確認できないため、デプロイ時に確認する
