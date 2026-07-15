# モジュール開発ガイド

BuildOne の各業務システムは「モジュール」として `app/Features/{Name}`（Coreは `app/Core/{Name}`）に置く。

## モジュールの生成

```bash
php artisan buildone:new-module Entry              # app/Features/Entry
php artisan buildone:new-module Auth --group=Core  # app/Core/Auth
```

生成される縦割りスライス:

```
app/Features/Entry/
├── EntryServiceProvider.php                         … 自動配線 + DIバインド
├── Domain/
│   ├── Entry.php                                    … Eloquentモデル
│   ├── Contracts/EntryRepository.php                … 永続化の契約
│   └── （必要に応じて）Data/EntryData.php           … DTO
├── Application/
│   └── Services/EntryService.php                    … ユースケース
├── Infrastructure/
│   ├── Repositories/EloquentEntryRepository.php     … 契約の実装
│   └── Database/
│       ├── Migrations/xxxx_create_entries_table.php
│       └── Factories/EntryFactory.php
├── Presentation/
│   ├── Http/Controllers/EntryController.php
│   ├── Routes/web.php                               … /entries
│   └── Views/index.blade.php                        … entry::index
└── Tests/EntryTest.php                              … Pest（RefreshDatabase）
```

生成後の手順:

```bash
composer dump-autoload   # 新しい名前空間を認識させる
php artisan migrate      # テーブル作成
php artisan test         # モジュールのテストが緑になることを確認
```

`/entries` を開くと一覧が表示される。設定ファイルの編集は不要（`ModuleServiceProvider` が自動配線する）。

## リファレンス例

`app/Features/Task` は `buildone:new-module` が生成した参照用モジュール。フレームワークの全レイヤーが最小構成で動く例になっている（不要なら `rm -rf app/Features/Task` で削除可）。

## 規約

- **層の依存方向を守る**: Presentation → Application → Domain。Domain から Infrastructure を直接参照しない（契約経由）。
- **Coreを汚さない**: 業務固有のロジックを `app/Core` に入れない。Coreは全モジュール共通の基盤のみ。
- **共通コンポーネントを再利用**: 独自の入力/テーブル/モーダルを作らず、UI Kit（v2.5〜）を使う。
- **テストファースト**: 各モジュールに Feature テストを必ず用意する。
- **命名**: モジュール名は StudlyCase 単数（Entry, Shift）。テーブルは複数形スネーク（entries, shifts）。

## モジュールの削除

```bash
rm -rf app/Features/Entry
composer dump-autoload
```

`ModuleRegistrar` は存在するディレクトリのみ走査するため、削除すれば自動的に無効化される（`down()` マイグレーションは必要に応じ事前に実行）。
