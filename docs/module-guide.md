# モジュール開発ガイド

BuildOne の各業務システムは「モジュール」として `app/Features/{Name}`（共通基盤は `app/Core/{Name}`）に置く。Laravel標準寄りのフラット構成。

## モジュールの生成

```bash
php artisan buildone:make-module Entry              # app/Features/Entry
php artisan buildone:make-module Auth --group=Core  # app/Core/Auth
```

生成物:

```
app/Features/Entry/
├── EntryServiceProvider.php                  自動配線 + （必要なら）DIバインド
├── Models/Entry.php
├── Http/Controllers/EntryController.php      index()（Entry::latest()->get() を一覧表示）
├── Database/Migrations/xxxx_create_entries_table.php
├── Database/Factories/EntryFactory.php
├── Routes/web.php                            /entries
├── Views/index.blade.php                     entry::index
└── Tests/EntryTest.php                       Pest（RefreshDatabase）
```

生成後:

```bash
composer dump-autoload   # 新しい名前空間を認識させる
php artisan migrate      # テーブル作成
php artisan test         # モジュールのテストが緑になることを確認
```

`/entries` を開くと一覧が表示される（設定ファイルの編集は不要）。

## いつ何を足すか（軽量方針）

| やりたいこと | 足すもの |
|---|---|
| 入力を検証する | `Http/Requests/StoreEntryRequest.php`（FormRequest） |
| 複雑なユースケース | `Actions/` または `Services/` にクラスを切り出す |
| 認可 | `Policies/EntryPolicy.php` |
| 永続化を差し替え可能にしたい | `App\Domain\Contracts\Repository` を継承した契約 ＋ `EloquentRepository` 実装、`ServiceProvider::bindings()` で束ねる |
| 一覧の整形 | Eloquent API Resource |

**足りるうちは足さない。** 単純なCRUDはモデル＋コントローラで十分。

## リファレンス例

`app/Features/Task` は `buildone:make-module` が生成した参照モジュール。フレームワークが動く最小例（不要なら `rm -rf app/Features/Task`）。

## 規約

- **Coreを汚さない**: 業務固有ロジックを `app/Core` に入れない。Coreは全モジュール共通の基盤のみ。
- **共通コンポーネントを再利用**: 画面は `resources/views/components/` の UI Kit を使う（[ui-guide.md](ui-guide.md)）。
- **テストファースト**: 各モジュールに Feature テストを必ず用意。
- **命名**: モジュール名は StudlyCase 単数（Entry, Shift）。テーブルは複数形スネーク（entries, shifts）。

## モジュールの削除

```bash
rm -rf app/Features/Entry
composer dump-autoload
```

`ModuleRegistrar` は存在するディレクトリのみ走査するため、削除すれば自動的に無効化される（テーブルが不要なら事前に `down()` マイグレーションを実行）。
