# コーディング規約（BuildOne Framework）

最優先の設計基準は `docs/framework-spec-v2.md`。本書はその運用ルール。

## 品質ゲート

すべての変更は以下を通すこと。CI（`.github/workflows/ci.yml`）でも実行される。

```bash
composer lint       # Laravel Pint で整形
composer analyse    # PHPStan level 6
composer test       # Pest（Feature/Unit/Modules）
composer ci         # 上記を一括（lint:test → analyse → test）
```

- **Pint**: Laravel プリセット。コミット前に `composer lint` で自動整形。
- **PHPStan**: level 6（`phpstan.neon`）。将来的に段階的に引き上げる。モジュールの `Tests/` は解析対象外。
- **Pest**: 全モジュールに Feature テストを用意。DBを使うテストは `uses(Tests\TestCase::class, RefreshDatabase::class)`。

## 設計原則

- **DRY / SOLID / Clean Architecture**: 重複を避け、依存は抽象（契約）に向ける。
- **層の依存方向**: Presentation → Application → Domain。Infrastructure は Domain の契約を実装する。
- **Fat Controller 禁止**: Controller は入出力のみ。業務ロジックは Service / Action へ。
- **FormRequest**: 入力検証は必ず FormRequest で行う。
- **Policy**: 認可は Policy に集約（Core\Auth 導入後）。
- **DTO**: 層をまたぐデータは `App\Domain\DataTransferObject` を継承した DTO で渡す（Eloquentモデルを持ち回らない）。
- **Result**: 失敗しうるユースケースは例外ではなく `App\Domain\Support\Result` で成否を返すことを検討する。
- **型を書く**: `declare(strict_types=1)`、引数・戻り値・プロパティに型を付ける。ジェネリクスは PHPDoc（`@template` / `@extends`）で表現する。

## Claude Code / AI 開発ルール（v2.0設計書より）

- 既存設計を尊重する（この設計書を最優先ルールとして扱う）
- **Coreを汚さない**（業務固有コードをCoreに入れない）
- 共通コンポーネントを再利用する
- README / docs を更新する
- **危険操作前は確認**（削除・上書き・本番反映・push は対象と差分を確認してから）
- 環境調査から開始し、推測実装をしない

## Git

- ブランチ: `main` / `develop` / `feature/*`
- コミット: `feat` / `fix` / `docs` / `refactor` / `chore`（例: `feat: add Entry module`）
- `.env` はコミットしない。秘密情報をソースに書かない。
