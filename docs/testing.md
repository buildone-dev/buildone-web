# テストガイド（BuildOne Framework）

## 実行

```bash
composer test        # Pest（Unit / Feature / Modules）
php artisan test     # 同上
composer ci          # Pint(検査) → PHPStan lv6 → Pest
```

テストDBは SQLite `:memory:`（`phpunit.xml`）。DBを使うテストは `uses(Tests\TestCase::class, RefreshDatabase::class)`。

## テストスイート

| スイート | 対象 |
|---|---|
| Unit | `tests/Unit` |
| Feature | `tests/Feature`（LP / UI Kit 等） |
| Modules | `app/Core/**` と `app/Features/**` の `*Test.php`（モジュール自身のテスト） |

モジュールのテストはモジュール内 `Tests/` に置き、`Modules` スイートが自動収集する。

## 現状（v2.0 基盤）

16件・緑。内訳: 公式LP（トップ200/プライバシー200/404/見出し/システム名/問い合わせ未設定/モバイルメニュー/SEO/本番デバッグ非表示）、UI Kitプレビュー、参照モジュール Task。

## 最低限そろえるテスト（機能実装時）

決定版プロンプト フェーズ10準拠:

- トップ / ダッシュボード表示、ログイン画面
- 未認証アクセス制御、管理者アクセス許可、一般ユーザー拒否
- ユーザー一覧 / 作成 / 更新、バリデーション、権限チェック
- 監査ログ記録
- UIの主要テキスト、404
- `APP_ENV=production` でデバッグ情報を出さない設計

## 静的解析・整形

- PHPStan（Larastan）level 6。`phpstan.neon`。モジュールの `Tests/` は解析対象外（Pest手続きDSLのため）。
- Laravel Pint（Laravelプリセット）。コミット前に `composer lint`。
