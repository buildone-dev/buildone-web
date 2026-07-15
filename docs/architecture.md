# アーキテクチャ（BuildOne Framework）

> 最優先の設計基準は `prompts/framework-foundation-build.md`（決定版）と `docs/framework-spec-v2.md`。
> 本書はその実装方針を具体化したもの。統合の経緯は `docs/plans/framework-foundation-plan.md`。

## 目的

100種類以上の業務システム（Entry / Time / Shift / Reserve / Report …）を**同じコードベース**で高速開発する。「フレームワーク（土台）」と「モジュール（各業務システム）」を分離し、モジュールは規約に従うだけで自動的にアプリへ組み込まれる。

## 設計哲学: 軽量な Laravel標準寄り Feature First

過度に複雑な Clean Architecture を**強制しない**。Laravel の標準構造を活かし、機能（Feature）単位で凝集させる。

- **Feature First**: 1つの業務システムに関わるコードを1ディレクトリに集約（追加・削除・理解が機能単位で完結）。
- **Repository / DTO / Service / Action は必要な機能でだけ使う**。単純なCRUDはモデル＋コントローラで十分。永続化の差し替えや複雑な境界が必要になったときに、共通カーネル（下記）を使って段階的に導入する。
- Fat Controller を避け、複雑化したらロジックを Action / Service へ切り出す。
- Core は業務固有処理を持たない。

理由: 「1週間で業務システムを作る」速度と、人／AIの理解しやすさを最優先する。層の分割はコストであり、必要になるまで支払わない。

## ディレクトリ構成

```
app/
├── Core/            共通基盤（Auth, Authorization, Dashboard, Menu, Notifications, Settings, Audit, UI, Support）
├── Features/        業務モジュール（Users, Roles, 将来 Entry/Time/…）
├── Domain/          共通カーネル（Contracts\Repository, DataTransferObject, Support\Result）… 使いたい時に使う道具
├── Infrastructure/  共通インフラ（Persistence\EloquentRepository, Modules\* = 自動配線）
├── Providers/       App / Framework ServiceProvider
├── Console/         buildone:* CLI
└── Http/, Models/   Laravel標準（公式サイトLPのルート/ビューはここ）

resources/views/
├── components/      UI Kit（Blade Components）
├── layouts/         公開LP用レイアウト（@extends）
└── pages/           管理画面等のページ
```

### モジュールの内部構成（Laravel標準寄り・フラット）

```
app/Features/{Name}/
├── {Name}ServiceProvider.php     自動配線（name() のみ。必要なら bindings()）
├── Models/{Name}.php
├── Http/Controllers/{Name}Controller.php
├── Http/Requests/                （検証が要るとき）
├── Database/Migrations/xxxx_create_{table}_table.php
├── Database/Factories/{Name}Factory.php
├── Routes/web.php                /{table}
├── Views/index.blade.php          {name}::index
└── Tests/{Name}Test.php           Pest
```

深い4層ネストは強制しない。Action / Service / Policy / Repository は必要になったら同ディレクトリ内に足す。

## モジュール自動配線

`App\Providers\FrameworkServiceProvider` が起動時に `App\Infrastructure\Modules\ModuleRegistrar` を呼び、`app/Core/*` と `app/Features/*` を走査して各 `{Module}ServiceProvider` を登録する。

`ModuleServiceProvider`（基底）が規約パスを自動読み込み:

- `Database/Migrations` → マイグレーション
- `Views` → ビュー（名前空間 = モジュール名。`view('task::index')`）
- `Routes/web.php` → ルート
- `bindings()` → DI（Repository契約→実装 など。任意）

→ **モジュールを置くだけで、設定ファイルを触らずにアプリへ組み込まれる。**

## 共通カーネル（任意利用の道具）

| クラス | 役割 |
|---|---|
| `App\Domain\Contracts\Repository<TModel>` | 永続化の抽象。差し替えが要る機能でだけ使う |
| `App\Infrastructure\Persistence\EloquentRepository<TModel>` | 上記のEloquent共通実装。`model()` のみ実装 |
| `App\Domain\DataTransferObject` | 層をまたぐ複雑な境界データの基底 |
| `App\Domain\Support\Result` | 例外に頼らず成功/失敗を返す値オブジェクト |

## 品質ゲート

`composer ci`（= Pint → PHPStan lv6 → Pest）。CI は `.github/workflows/ci.yml`、依存更新は `.github/dependabot.yml`。詳細は [coding-rule.md](coding-rule.md) / [testing.md](testing.md)。

## ロードマップ

概要は [roadmap.md](roadmap.md)。v2.0（基盤）は本書の範囲、UI Kit拡充・Core基盤・認証は順次。
