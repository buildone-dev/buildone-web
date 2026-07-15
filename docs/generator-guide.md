# ジェネレータ / CLI ガイド（BuildOne CLI）

BuildOne Framework は `buildone:*` の Artisan コマンドで開発を高速化する。

## 実装済み（v2.0）

### `buildone:new-module`

モジュール（Feature Firstの縦割りスライス一式）を生成する。

```bash
php artisan buildone:new-module {Name} [--group=Features|Core]
```

| 引数 / オプション | 説明 |
|---|---|
| `Name` | モジュール名（StudlyCase 単数。例: `Entry`） |
| `--group` | 配置グループ。`Features`（既定）または `Core` |

- テンプレートは `stubs/buildone/module/*.stub`。プレースホルダ（`__NAMESPACE__` / `__MODULE__` / `__MODULE_LOWER__` / `__MODULE_SNAKE__` / `__TABLE__`）を置換して出力する。
- 生成物は Laravel Pint 整形済み・PHPStan lv6 通過済みになるようスタブを調整してある。
- スタブを変更したら、生成物が品質ゲートを通ることを `composer ci` で確認すること。

## ロードマップ（未実装）

v2.0設計書の CLI 一覧。優先度順に追加していく。

| コマンド | 内容 | 予定 |
|---|---|---|
| `buildone:crud {Module}` | 既存モジュールに index/create/edit/update/delete と UI 一式を生成 | v2.5 |
| `buildone:deploy` | `deploy/deploy.sh` のラッパー | v2.x |
| `buildone:test` | Pest 実行のラッパー（`composer test` と同等） | v2.x |
| `buildone:docs` | モジュールから API/画面ドキュメントを生成 | v2.x |

### AI Engine（v2.5〜）

設計書の「AI Helper」は、要件整理・CRUD生成・テスト生成・ドキュメント生成・レビュー支援・リファクタリング提案を担う。生成の入力はモジュールのスキーマ定義（フィールド一覧）を想定し、`buildone:crud` の拡張として実装する。

## 新しいコマンドの追加方法

1. `app/Console/Commands/BuildOne/` にコマンドクラスを作成（`buildone:` プレフィックス）。
2. Laravel 13 が `app/Console/Commands` を自動検出するため登録は不要。
3. スタブを使う場合は `stubs/buildone/` に置き、`strtr()` でプレースホルダ置換する（`NewModuleCommand` を参照）。
4. `composer ci` で Pint / PHPStan / Pest が緑であることを確認。
