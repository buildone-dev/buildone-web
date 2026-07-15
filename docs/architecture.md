# アーキテクチャ（BuildOne Framework）

> 最優先の設計基準は `docs/framework-spec-v2.md`（v2.0設計書）。本書はその実装方針を具体化したもの。

## 目的

100種類以上の業務システム（Entry / Time / Shift / Reserve / Report …）を**同じコードベース**で高速開発する。そのため「フレームワーク（土台）」と「モジュール（各業務システム）」を分離し、モジュールは規約に従うだけで自動的にアプリに組み込まれる構造にする。

## レイヤー構成（Clean Architecture）

各モジュールは4層の縦割り（Feature First）で構成する。依存の向きは常に **外→内**（Presentation → Application → Domain）で、Infrastructure は Domain の契約を実装する。

| レイヤー | 責務 | 例 |
|---|---|---|
| **Presentation** | 入出力（HTTP・Blade・ルート） | Controller / FormRequest / Resource / Views / Routes |
| **Application** | ユースケースの調停 | Service / Action |
| **Domain** | 業務ルール・エンティティ・契約 | Model / DTO / Contracts\Repository |
| **Infrastructure** | 外部世界の実装 | Eloquent Repository / Migration / Factory |

**依存性逆転**: Domain は `App\Domain\Contracts\Repository` という抽象にのみ依存し、具体的な Eloquent 実装（Infrastructure）を知らない。実装の差し替え（MySQL↔外部API等）が Domain に影響しない。

## ディレクトリ構成（採用理由）

v2.0設計書の指定に従い、以下を採用した。

```
app/
├── Core/            … フレームワークのCoreモジュール（認証・権限・監査ログ・通知・設定・テーマ・ダッシュボード等。Phase 2で追加）
├── Features/        … 業務モジュール（Entry, Time, Shift, ... 各々が縦割りスライス）
├── Domain/          … 全モジュール共通のドメインカーネル（DataTransferObject, Contracts\Repository, Support\Result）
├── Infrastructure/  … 共通インフラ（Persistence\EloquentRepository, Modules\* = モジュール自動配線機構）
├── Providers/       … AppServiceProvider, FrameworkServiceProvider（モジュール発見のカーネル）
├── Console/         … buildone:* CLI コマンド
├── Http/, Models/   … Laravel標準（公式サイトLPはここのルート/ビューに残置）
```

### なぜ Feature First（モジュール縦割り）か

- **凝集度**: 1つの業務システムに関わるコードが1ディレクトリに集まり、追加・削除・理解がモジュール単位で完結する（`rm -rf app/Features/Entry` で機能ごと除去できる）。
- **並行開発**: モジュール間の結合が疎なので、複数機能を同時並行で開発しても衝突しにくい。YouTubeの「1週間チャレンジ」単位と一致する。
- **AI協調**: 1モジュール=1コンテキストで完結するため、AIに渡す範囲が明確。
- レイヤーで縦割り（app/Controllers, app/Services… に全機能を混在）する方式は、機能が増えるほど各ディレクトリが肥大化し、機能単位の見通しが悪くなるため採用しない。

## モジュール自動配線

`App\Providers\FrameworkServiceProvider` が起動時に `App\Infrastructure\Modules\ModuleRegistrar` を呼び、`app/Core/*` と `app/Features/*` を走査して各 `{Module}ServiceProvider` を登録する。

各モジュールの `ModuleServiceProvider`（`App\Infrastructure\Modules\ModuleServiceProvider` を継承）は、規約パスを自動で読み込む:

- `Infrastructure/Database/Migrations` → マイグレーション
- `Presentation/Views` → ビュー（名前空間 = モジュール名。`view('task::index')`）
- `Presentation/Routes/web.php` → ルート
- `bindings()` → DIバインディング（Repository契約→Eloquent実装）

→ **モジュールを追加するだけで、設定ファイルを一切触らずにアプリへ組み込まれる。**

## 共通カーネル

| クラス | 役割 |
|---|---|
| `App\Domain\Contracts\Repository<TModel>` | 永続化の抽象（all/find/create/update/delete） |
| `App\Infrastructure\Persistence\EloquentRepository<TModel>` | 上記のEloquent共通実装。各モジュールは `model()` のみ実装 |
| `App\Domain\DataTransferObject` | 層をまたぐ不変データの基底 |
| `App\Domain\Support\Result` | 例外に頼らず成功/失敗を返す値オブジェクト |

## 品質ゲート

`composer ci`（= Pint → PHPStan lv6 → Pest）。CIは `.github/workflows/ci.yml`、依存更新は `.github/dependabot.yml`。詳細は [coding-rule.md](coding-rule.md)。

## ロードマップ

- **v2.0（現在）**: Framework基盤 — レイヤー/モジュール自動配線/共通カーネル/`buildone:new-module`/品質ゲート
- **v2.5**: `buildone:crud`（full CRUD生成）、UI Kit（Button/Card/Table/Modal/Form/Wizard/Chart/Timeline/Toast）
- **v2.x**: Core モジュール（認証・権限・監査ログ・通知・設定・テーマ・ダッシュボード）
- **v3.0**: SaaS化（マルチテナント・サブスク・API・Webhook）
- **v4.0**: Marketplace
