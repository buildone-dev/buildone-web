# ジェネレータ / CLI ガイド（BuildOne CLI）

BuildOne Framework は `buildone:*` の Artisan コマンドで開発を高速化する。

## 実装済み（v2.0）

### `buildone:make-module`（別名 `buildone:new-module`）

モジュール（Laravel標準寄りのフラットな縦割りスライス一式）を生成する。

```bash
php artisan buildone:make-module {Name} [--group=Features|Core]
```

| 引数 / オプション | 説明 |
|---|---|
| `Name` | モジュール名（StudlyCase 単数。例: `Entry`） |
| `--group` | 配置グループ。`Features`（既定）または `Core` |

生成物: ServiceProvider / Model / Controller / Migration / Factory / Routes / View / Pestテスト（[module-guide.md](module-guide.md)）。

- テンプレートは `stubs/buildone/module/*.stub`。プレースホルダ（`__NAMESPACE__` / `__MODULE__` / `__MODULE_LOWER__` / `__MODULE_SNAKE__` / `__TABLE__`）を `strtr()` で置換。
- 生成物は Laravel Pint 整形済み・PHPStan lv6 通過済みになるようスタブを調整済み。スタブ変更後は `composer ci` で確認すること。

## ロードマップ（未実装）

決定版プロンプトの CLI 一覧。優先度順に追加していく。

| コマンド | 内容 | 予定 |
|---|---|---|
| `buildone:doctor` | 環境・必須ディレクトリ・設定・書込権限・DB接続・キャッシュを診断 | v2.x |
| `buildone:make-crud {Module}` | 既存モジュールに index/create/edit/update/delete と UI 一式を生成 | v2.5 |
| `buildone:make-widget {Name}` | ダッシュボードWidgetを生成 | v2.5 |

### AI Helper（v2.5〜）

要件整理・CRUD生成・テスト生成・ドキュメント生成・レビュー支援・リファクタリング提案。入力はモジュールのフィールド定義を想定し、`buildone:make-crud` の拡張として実装する。

## 新しいコマンドの追加方法

1. `app/Console/Commands/BuildOne/` にクラスを作成（`buildone:` プレフィックス）。
2. Laravel 13 が `app/Console/Commands` を自動検出するため登録は不要。
3. スタブは `stubs/buildone/` に置き `strtr()` で置換（`NewModuleCommand` を参照）。
4. `composer ci` で Pint / PHPStan / Pest が緑であることを確認。
