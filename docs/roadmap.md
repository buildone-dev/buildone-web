# ロードマップ（BuildOne Framework）

| バージョン | 内容 | 状態 |
|---|---|---|
| **v2.0** | Framework基盤: レイアウト/UI Kit/モジュール自動配線/共通カーネル/`buildone:make-module`/品質ゲート（Pint・PHPStan lv6・Pest・CI・Dependabot） | 基盤・UI Kit 完了 |
| **v2.0** | 認証（ログイン/ログアウト）・認可（ロール/権限/Gate）・ユーザー管理・監査ログ・メニュー・管理ダッシュボード | **完了** |
| v2.x | Core基盤の残り: ダッシュボードWidget拡充 / 設定基盤 / 通知基盤、ロール編集UI、`buildone:doctor` | 予定 |
| v2.5 | `buildone:make-crud` / `make-widget`、UI Kit拡充、AI Helper | 予定 |
| v3.0 | SaaS化: マルチテナント / サブスクリプション / API / Webhook | 構想 |
| v4.0 | Marketplace（プラグイン配布） | 構想 |

## モジュール（業務システム）ロードマップ

Entry → Time → Shift → Reserve → Report → Stock → CRM → Invoice → Customer → Workflow

第1弾は **BuildOne Entry**（QR・ICカード入退室管理）。Core基盤（認証・権限・メニュー・監査ログ）が整い次第、`buildone:make-module Entry` を起点に着手する。

## v2.0 の残タスク（Core基盤・認証）

決定版プロンプト（`prompts/framework-foundation-build.md`）のフェーズ6〜7に相当。マイルストーンは `docs/plans/framework-foundation-plan.md` の M3（Core基盤）・M4（認証）。
