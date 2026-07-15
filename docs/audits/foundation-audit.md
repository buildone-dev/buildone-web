# 基盤現状監査（foundation-audit）

> フェーズ1（現状調査）の記録。秘密情報は含めない。作成 2026-07-15。

## ローカル環境

| 項目 | 値 |
|---|---|
| OS | Windows 11（開発は WSL Ubuntu 24.04 併用） |
| 作業ディレクトリ | `C:\dev\buildone-web` |
| Git | リポジトリ。ブランチ `feature/buildone-framework`（`main` にLP公開版、`develop` あり） |
| PHP | 8.3.30（本番さくら: 8.3.31） |
| Composer | 2.10（`composer.phar`） |
| Node.js / npm | 24.13.0 / 11.6.2 |
| Laravel | 13.20（`laravel/framework ^13.8`） |
| テスト | Pest 4 + PHPUnit 12（`php artisan test`） |
| Docker | 不使用 |
| `.env` | 存在（内容は非表示） |

## 既存設計（このブランチ時点）

- **公式サイトLP**（main由来）: `resources/views/`（home / privacy / errors）、`routes/web.php`。本番公開中・DBレス。
- **Framework基盤**（このブランチで追加、コミット `fa47e7d`）:
  - 共通カーネル: `app/Domain`（Contracts\Repository, DataTransferObject, Support\Result）、`app/Infrastructure`（Persistence\EloquentRepository, Modules\*）
  - モジュール自動配線: `FrameworkServiceProvider` + `ModuleRegistrar` + `ModuleServiceProvider`
  - CLI: `buildone:new-module`（4層縦割りスライス生成）
  - 参照例: `app/Features/Task`
  - `app/Core`: 空（プレースホルダのみ）
- **認証方式**: 未実装（Laravel標準 `User` モデルのみ存在）
- **UIライブラリ**: Tailwind CSS 4 + Vite 8。Bladeコンポーネントは未整備（LPは直書き）。
- **テスト**: 15件（LP 14 + モジュール1）緑
- **CI / 静的解析 / 整形**: GitHub Actions（`.github/workflows/ci.yml`）、Larastan lv6、Pint、Dependabot 導入済み
- **デプロイ**: `deploy/deploy.sh`（rsync over SSH、案B構成）。`docs/deployment.md` / `docs/server-audit.md` あり。

## 設計書との整合

読み込んだ設計資料: `docs/framework-spec-v1.md`（v1.0）、`docs/framework-spec-v2.md`（v2.0）、`prompts/framework-framework-*`、`prompts/framework-foundation-build.md`（最新・最詳細）、`CLAUDE.md`。

**検出した方針の相違**（詳細と統合案は `docs/plans/framework-foundation-plan.md`）:

- 最新プロンプト フェーズ4は「**過度に複雑な Clean Architecture を強制せず、Laravel標準構造を活かして Feature First で整理**」「Repository はスワップが必要な箇所だけ」「DTO は複雑な境界だけ」を求める。
- 一方、現状のコミット済み基盤は、各モジュールを Presentation/Application/Domain/Infrastructure の4層に必ず分割し、Repository契約＋Eloquent実装を既定で生成する（やや重い）。
- また Core の内部構成が、最新プロンプトでは概念別（Auth / Menu / Notifications / …）だが、現状は未実装。

→ 最新プロンプトが最も詳細かつ「1週間開発」の速度・理解容易性を重視しているため、**より軽量な Laravel標準寄り Feature First へ寄せる統合案**を推奨する（計画書参照）。
