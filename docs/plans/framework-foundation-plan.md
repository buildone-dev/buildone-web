# Framework 基盤 実装計画（framework-foundation-plan）

> フェーズ2（実装計画）の記録。最新プロンプト `prompts/framework-foundation-build.md` と
> 既存コミット済み基盤の統合案。作成 2026-07-15。

## 技術選定

- Laravel 13 / PHP 8.3+（本番さくら 8.3.31）/ Blade / Tailwind CSS 4 / Vite 8
- 初期DB: SQLite 許容 → MySQL 移行可能（さくらは MySQL 提供あり）
- 品質: Pint / Larastan(PHPStan lv6) / Pest / GitHub Actions / Dependabot（導入済み）

## 方針の相違と統合案（要判断）

最新プロンプト フェーズ4は「**Clean Architecture を強制せず Laravel標準を活かした Feature First**」「Repository は必要な箇所のみ」を求める。現状のコミット済み基盤はモジュールを4層に必ず分割する（やや重い）。

### 推奨: 軽量な Laravel標準寄り Feature First へ寄せる

- モジュール内部を **フラット**に（`app/Features/{X}/` 直下に Controllers / Requests / Actions / Services / Policies / Models / Views / Tests）。4層の深いネストは強制しない。
- **Repository / DTO は任意**（永続化スワップや複雑な境界がある機能でのみ使用）。共通の `App\Domain\Contracts\Repository` と `EloquentRepository` は「使いたい時に使える道具」として残す。
- `buildone:new-module` の生成テンプレートを軽量版へ差し替え、参照例 `app/Features/Task` を軽量構成へ作り直す。
- 共通カーネル（`app/Domain`, `app/Infrastructure`, モジュール自動配線）は**維持**（設計書のディレクトリ指定と一致し、有用）。

> この方針転換により、コミット済みの重い4層ジェネレータを作り直す。git 履歴には残るため安全に戻せる。

## ディレクトリ構成（統合後）

```
app/
├── Core/            Auth, Authorization, Dashboard, Menu, Notifications, Settings, Audit, UI, Support
├── Features/        Users, Roles, Shared, （将来 Entry/Time/…）
├── Domain/          共通カーネル（Contracts, DataTransferObject, Support\Result）
├── Infrastructure/  Persistence\EloquentRepository, Modules\*（自動配線）
└── Providers/       App / Framework ServiceProvider
resources/views/     components, layouts, pages, features
```

## Core と Feature の責務

- **Core**: 全モジュール共通の基盤のみ（認証・認可・メニュー・ダッシュボード・設定・通知・監査ログ・UI・Support）。業務固有処理を持たない。
- **Feature**: 業務単位（Users, Roles, 将来 Entry 等）。Core の基盤を利用する。

## 認証・認可方針

- Laravel 標準（Breeze 相当の自前 or Fortify系）でログイン/ログアウト/プロフィール。
- ロール・権限は文字列を一元管理（enum or 定数 + 中間テーブル）。Policy/Gate で認可。
- `super_admin` 相当は監査可能に。初期管理者作成は README + Seeder（本番固定PW禁止）。

## UI Kit 方針

Blade Components で最小部品（Layout/Header/Sidebar/Button/Card/Input/Alert/Table/Modal/EmptyState/Skeleton …）を `resources/views/components/` に整備。disabled/loading/error/focus/a11y/ダークモード拡張余地を考慮。トークンは `resources/css/app.css`（既存ブランドカラー）。

## テスト戦略 / CI

- Feature中心（表示・認証・認可・ユーザーCRUD・バリデーション・監査ログ・404・本番デバッグ非表示）。
- CI（導入済み）: composer install → npm build → Pint → PHPStan → Pest。

## さくら適合性

- SSH: `buildone-sakura`（設定済み・接続確認済み）。PHP 8.3 / rsync あり / Composer・Node なし → ローカルビルド転送。詳細 `docs/server-audit.md`（→ `docs/server-environment.md` に統合予定）。
- 配置: `~/buildone-web`（非公開）+ `~/www`（公開）。初回は `rsync --delete` を使わない。
- 本番公開は基盤段階では強行しない（現LPは main で公開中・不変）。

## 初期版で作らないもの / 将来

- 作らない: 多要素認証、複雑な組織構造、完全な CRUD Generator、外部通知の実送信、SaaSマルチテナント。
- 将来（ロードマップ `docs/roadmap.md`）: `buildone:make-crud` / `make-widget` / `doctor`、UI Kit拡充、SaaS化。

## マイルストーン

1. **M1 アーキテクチャ確定・軽量化**: 生成テンプレートを軽量Feature構成へ。`app/Core` 骨組み作成。（本計画の承認後）
2. **M2 UI Kit + 共通レイアウト**: App/Guest Layout, Header/Sidebar/Mobile Nav, 主要コンポーネント。
3. **M3 Core基盤**: メニュー / ダッシュボードWidget / 設定 / 通知 / 監査ログ の骨組み。
4. **M4 認証・ユーザー・ロール・権限**: ログイン〜管理画面、Policy、テスト。
5. **M5 CLI拡張**: `buildone:doctor` 追加、`make-module` を軽量版に。
6. **M6 ドキュメント整備**: security / testing / server-environment / roadmap / prompts/*。

## リスク

- 方針転換によるジェネレータ作り直しの手戻り（git で軽減）。
- スコープが大きく複数フェーズに跨る（M1→M6を順次・各フェーズ後に報告）。
- さくら共有サーバーでの queue/cron 制約（基盤段階では未使用）。
