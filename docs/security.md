# セキュリティ方針（BuildOne Framework）

## 秘密情報

- `.env` はコミットしない（`.gitignore` 済み）。秘密情報をソースに書かない。
- SSH秘密鍵・パスワード・APIキーを出力・保存・ログ記録しない。
- 本番は `APP_DEBUG=false`（`docs/deployment.md`）。エラー画面に内部情報を出さない。

## 認証・認可（実装時の規約）

- パスワードをログ・レスポンスに出さない。ハッシュ化必須。
- 権限名は文字列を散在させず一元管理（enum/定数）。認可は Policy/Gate に集約。
- `super_admin` 相当は監査可能に。初期管理者は README の手順で作成し、Seeder に本番用固定パスワードを置かない。
- 未認証アクセス・権限外アクセスをテストで担保（`docs/testing.md`）。

## 監査ログ

`actor / action / subject / metadata / IP / user agent / occurred_at` を記録（Core\Audit、M3で実装）。**機密情報・パスワードはログに残さない。**

## Web公開

- Laravelプロジェクト全体を公開ディレクトリに置かない。docroot は `public` 相当のみ（`docs/deployment.md` 案B）。
- `.env` / `composer.json` / `storage` / `.git` に外部からアクセスできないことをデプロイ後に検証（本番LPで403/404を確認済み）。
- セキュリティヘッダー（`X-Content-Type-Options` / `X-Frame-Options` / `Referrer-Policy` / `Permissions-Policy`）を `.htaccess` で付与。HTTPS強制。

## フロントエンド

- 外部JS/フォントに依存しない（サプライチェーン・可用性リスクの低減）。
- ユーザー入力は Blade の自動エスケープを基本とし、`{!! !!}` は使わない。
- フォームは CSRF トークン必須（Laravel標準）。

## 依存関係

- Dependabot（composer / npm / github-actions）で毎週更新PRを受ける。
- `composer audit` を随時実行（CIに追加予定）。
