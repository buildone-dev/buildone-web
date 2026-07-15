# プロンプト: 新しいモジュールを作る

BuildOne Framework に業務モジュール `{Name}`（例: Entry）を追加してください。

## 前提

- 最優先ルール: `CLAUDE.md`、`docs/architecture.md`、`docs/module-guide.md`、`docs/coding-rule.md`、`docs/ui-guide.md`。
- 軽量な Laravel標準寄り Feature First。過剰設計しない。Coreを汚さない。共通UI Kitを再利用する。

## 手順

1. `php artisan buildone:make-module {Name}` で雛形を生成。
2. `Models/{Name}.php` に必要なカラム／`$fillable`／リレーションを定義し、マイグレーションを更新。
3. 画面は `resources/views/components/`（UI Kit）を使って `Views/` に実装。管理画面は `<x-layouts.app>`。
4. 一覧以外（作成・編集・削除）が要るなら Controller にアクションを追加。検証は `Http/Requests/` の FormRequest。複雑なら `Actions/` へ。
5. 認可が要るなら `Policies/`。永続化差し替えが要るなら Repository契約＋実装を追加し `ServiceProvider::bindings()` で束ねる。
6. `Tests/{Name}Test.php` に Feature テスト（表示・作成・バリデーション・権限）。
7. `composer dump-autoload && php artisan migrate && composer ci` で緑を確認。

## 完了条件

- 画面が UI Kit で表示され、モバイルで崩れない。
- テスト・PHPStan・Pint が緑。
- `.env` や秘密情報をコミットしていない。
- 必要なら `docs/roadmap.md` のモジュール状態を更新。
