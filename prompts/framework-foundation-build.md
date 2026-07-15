# BuildOne Framework 基盤構築プロンプト

あなたは、Laravel、PHP、Tailwind CSS、Vite、テスト設計、CI/CD、セキュリティ、共有レンタルサーバー運用に精通したシニアソフトウェアアーキテクトです。

このプロジェクトでは、今後100種類以上の業務システムを高速かつ一貫した品質で開発するための共通基盤として、**BuildOne Framework** を構築します。

単発のLaravelアプリケーションではなく、以下の製品群へ継続的に展開できる再利用可能な基盤を作ってください。

- BuildOne Entry
- BuildOne Time
- BuildOne Shift
- BuildOne Reserve
- BuildOne Report
- BuildOne Stock
- BuildOne CRM
- その他、今後追加される業務システム

---

# 0. 最優先で読むファイル

作業開始前に、プロジェクト内の以下のファイルを必ず探索し、存在するものをすべて読んでください。

- `CLAUDE.md`
- `README.md`
- `BuildOne_Framework_v1.0_設計書.md`
- `BuildOne_Framework_v2.0_設計書.md`
- `BuildOne_Framework_v2.0_設計書.docx`
- `docs/**/*.md`
- `prompts/**/*.md`

DOCXを直接読めない場合は、同内容のMarkdown版があるか確認してください。Markdown版がなければ、DOCXをテキスト化して `docs/reference/framework-v2.md` として保存してください。

これらのファイルに記載された方針を、本プロンプトより優先する必要がある場合は、矛盾点を整理してから安全な統合案を提示してください。

---

# 1. プロジェクト基本情報

## ブランド

- ブランド名: BuildOne
- キャッチコピー: 社長、そのシステム作れます。
- YouTubeチャンネル名: BuildOne | 社長、そのシステム作れます。
- YouTubeハンドル: `@buildonejp`
- 初期ドメイン: `https://buildone.sakura.ne.jp`

## ミッション

AIと人が協調し、会社で本当に使える業務システムを、数日から1週間で企画・設計・開発・検証・公開できる状態を実現する。

## 今回の対象

今回は、BuildOne Entryなどの業務固有機能は実装しません。

まず、すべてのBuildOne製品が利用する以下の共通基盤を構築します。

- プロジェクト構造
- 認証の拡張ポイント
- ユーザー管理の骨組み
- ロール・権限の骨組み
- 共通レイアウト
- UI Kit
- 設定基盤
- 通知基盤
- 監査ログ基盤
- メニュー基盤
- ダッシュボード基盤
- モジュール構造
- テスト環境
- 静的解析
- コード整形
- CI
- ドキュメント
- 将来のCLI・CRUD Generator用の拡張ポイント

---

# 2. 絶対に守る安全ルール

- 作業前に既存ファイルとGit状態を確認する
- 既存コードを無断で削除しない
- `rm -rf` を使用しない
- 既存ファイルを上書きする前に差分を確認する
- `.env`、秘密鍵、パスワード、APIキーをGitへ追加しない
- 本番用の秘密情報をソースコードへ書かない
- SSH秘密鍵の内容や保存場所を出力しない
- 公開鍵認証からパスワード認証へ勝手に切り替えない
- GitHubへのpush、本番デプロイ、既存環境の削除は、対象を確認した後に行う
- さくらのレンタルサーバーに対して `sudo` やOSパッケージ管理を前提にしない
- 不明点を推測だけで実装しない
- Laravelプロジェクト全体をWeb公開ディレクトリへ置かない
- 本番では `APP_DEBUG=false`
- データベース導入を初期段階で必須にしない
- 過剰設計を避け、拡張可能な最小基盤から始める

---

# 3. 作業の進め方

以下のフェーズを順番に実施してください。

各フェーズ終了時に、実施内容・判断理由・未解決事項を簡潔に報告してください。

---

# フェーズ1: 現状調査

最初にコードを変更せず、以下を調査してください。

## ローカル環境

- OS
- 現在のディレクトリ
- ファイル一覧
- Gitリポジトリか
- Gitのブランチと変更状態
- PHPのバージョン
- Composerのバージョン
- Node.jsのバージョン
- npmのバージョン
- Laravelプロジェクトの有無
- Laravelのバージョン
- テスト環境の有無
- Docker利用の有無
- `.env` の有無。ただし内容は表示しない

## 既存設計

- ディレクトリ構成
- 認証方式
- UIライブラリ
- 既存のCoreやFeature構造
- テスト
- CI
- 静的解析
- コード整形
- デプロイ関連ファイル
- ドキュメントの充実度

## 出力

調査結果を以下に保存してください。

`docs/audits/foundation-audit.md`

秘密情報は含めないでください。

---

# フェーズ2: 実装計画

調査結果と設計書を基に、詳細な実装計画を作成してください。

以下を含めてください。

- 採用するLaravelバージョン
- 必要PHPバージョン
- 技術選定
- ディレクトリ構成
- CoreとFeatureの責務
- 認証・認可方針
- UI Kit方針
- 設定・通知・監査ログ方針
- テスト戦略
- CI戦略
- さくらのレンタルサーバーへの適合性
- 初期版で作らないもの
- 将来追加するもの
- リスク
- マイルストーン

実装計画を以下へ保存してください。

`docs/plans/framework-foundation-plan.md`

計画を作成した後、安全に進められる範囲はそのまま実装へ進んでください。

---

# フェーズ3: Laravel基盤の初期化

Laravelプロジェクトが存在しない場合は、新規作成してください。

推奨プロジェクト名:

`buildone-framework`

既存Laravelプロジェクトがある場合は、設計書に適合できるか評価し、再作成せずに移行できるなら既存を活用してください。

## 基本設定

- `APP_NAME=BuildOne`
- ロケール: `ja`
- フォールバックロケール: `ja`
- タイムゾーン: `Asia/Tokyo`
- 初期URL: `https://buildone.sakura.ne.jp`
- Blade
- Tailwind CSS
- Vite
- JavaScriptは必要最小限
- 初期DBはSQLiteを許容
- MySQLへ移行可能な構成
- `.env.example` を整備
- `.gitignore` を確認
- `APP_KEY` は環境ごとに生成
- Laravel標準機能を不要に複製しない

---

# フェーズ4: アーキテクチャの骨組み

BuildOne Frameworkは、過度に複雑なClean Architectureを強制せず、Laravelの標準構造を活かしながらFeature Firstで整理してください。

推奨構成:

```text
app/
├── Core/
│   ├── Auth/
│   ├── Authorization/
│   ├── Dashboard/
│   ├── Menu/
│   ├── Notifications/
│   ├── Settings/
│   ├── Audit/
│   ├── UI/
│   └── Support/
├── Features/
│   ├── Users/
│   ├── Roles/
│   └── Shared/
├── Domain/
├── Infrastructure/
└── Providers/

resources/
├── views/
│   ├── components/
│   ├── layouts/
│   ├── pages/
│   └── features/
├── css/
└── js/

docs/
prompts/
tests/
```

## 方針

- Laravel標準のService Providerを活用
- Feature単位でController、Request、Action、Service、Policy、View、Testを整理可能にする
- Repository Patternは、永続化の差し替えが必要な箇所だけに使う
- DTOは複雑な境界データに限定する
- Actionクラスは1ユースケースを明確に表現する場合に使う
- Fat Controllerを避ける
- Coreは業務固有処理を持たない
- BuildOne Entryなどは将来 `app/Features/Entry` として追加できるようにする

構造の意図を `docs/architecture.md` に記録してください。

---

# フェーズ5: 共通レイアウトとUI Kit

BtoB向けで、信頼感・可読性・保守性を重視したUI基盤を構築してください。

## ブランドカラー

- Primary: `#2563EB`
- Dark: `#0F172A`
- Success: `#22C55E`
- Background: `#F8FAFC`
- White: `#FFFFFF`

## デザイン原則

- モバイルファースト
- 広い余白
- 過剰なアニメーションを避ける
- 日本語の可読性を優先
- アクセシビリティを考慮
- `prefers-reduced-motion` に対応
- 特定ブランドを複製しない
- 外部フォントがなくても崩れない

## 最小UIコンポーネント

Blade Componentsとして、最低限以下を用意してください。

- App Layout
- Guest Layout
- Header
- Sidebar
- Mobile Navigation
- Button
- Link Button
- Card
- Stat Card
- Input
- Textarea
- Select
- Checkbox
- Radio
- Form Field
- Validation Error
- Alert
- Badge
- Table
- Empty State
- Modal
- Dropdown
- Breadcrumb
- Pagination Wrapper
- Loading Indicator
- Skeleton
- Toast用の拡張ポイント

コンポーネントの使い方を `docs/ui-guide.md` に記載してください。

各コンポーネントは、見た目だけでなく以下を考慮してください。

- disabled
- loading
- error
- focus
- keyboard operation
- screen reader
- dark mode拡張余地

ダークモード自体は初期版で必須ではありませんが、トークン化しやすい構造にしてください。

---

# フェーズ6: Core基盤

## 6.1 メニュー基盤

以下に対応できるメニューシステムの骨組みを作ってください。

- Coreメニュー
- Featureごとのメニュー登録
- 権限による表示制御
- active状態
- モバイル・PC共通定義

メニュー項目をBladeへ直接大量記述しない設計にしてください。

## 6.2 ダッシュボード基盤

業務固有の数値はまだ実装せず、Widgetを差し込める構造にしてください。

例:

- Stat Widget
- List Widget
- Activity Widget
- Chart Widget用拡張ポイント
- Empty Widget

## 6.3 設定基盤

初期段階ではDB依存を最小化し、以下を扱える設計にしてください。

- アプリ名
- ブランド情報
- YouTube URL
- 問い合わせURLまたはメール
- Feature flags
- UI設定

環境変数、設定ファイル、将来のDB設定を統合できる境界を設計してください。

## 6.4 通知基盤

Laravel Notificationを活用し、以下へ拡張できる骨組みを作ってください。

- database
- mail
- Slack
- LINE等のWebhook

初期版では外部サービスへの実送信は不要です。

## 6.5 監査ログ基盤

最低限、以下を記録できる設計を作ってください。

- actor
- action
- subject type
- subject id
- metadata
- IP
- user agent
- occurred_at

初期版では、ユーザー更新などの代表操作で動作確認できる最小実装を行ってください。

機密情報やパスワードをログへ記録しないでください。

---

# フェーズ7: 認証・ユーザー・ロール・権限

Laravel標準または安定した公式系の構成を優先してください。

## 初期要件

- ログイン
- ログアウト
- パスワードリセット用拡張余地
- プロフィール表示・更新
- ユーザー一覧
- ユーザー作成
- ユーザー編集
- 有効・無効
- ロール
- 権限
- Policy/Gate
- 管理者専用画面

## 注意

- 不要な多要素認証や複雑な組織構造を初期版へ詰め込まない
- 権限名は文字列を散在させず、一元管理する
- `super_admin` 相当の扱いを設計する場合は、監査可能にする
- パスワードをログやレスポンスへ出さない
- 初期管理者作成方法をREADMEに記載する
- Seederで固定パスワードを本番利用しない

---

# フェーズ8: CLI・Generatorの拡張ポイント

完全なCRUD Generatorはまだ完成させなくて構いません。

ただし、将来以下を実装できる構造と設計書を用意してください。

```bash
php artisan buildone:make-module Customer
php artisan buildone:make-crud Customer
php artisan buildone:make-widget Revenue
php artisan buildone:doctor
```

## 今回実装するもの

最低限、以下のどちらかを作ってください。

1. `buildone:doctor`
   - 環境
   - 必須ディレクトリ
   - 設定
   - 書き込み権限
   - DB接続
   - キャッシュ
   を安全に診断する

または

2. `buildone:make-module`
   - 空のFeature構造
   - Service Provider
   - routes
   - views
   - test
   - README
   を生成する最小版

実装コストと価値を比較し、今回どちらを優先したか理由を記録してください。

Generator設計を `docs/generator-guide.md` に保存してください。

---

# フェーズ9: 品質管理

以下を導入してください。

- Laravel Pint
- PHPStanまたはLarastan
- PestまたはPHPUnit
- Feature Test
- Unit Test
- npm build
- GitHub Actions

## 必須コマンド

プロジェクトに適した形式で、以下を利用できるようにしてください。

```bash
composer test
composer lint
composer analyse
npm run build
```

必要であればComposer scriptsを定義してください。

## CI

GitHub Actionsで最低限以下を確認してください。

- Composer install
- npm install
- npm build
- Pint check
- Static analysis
- Tests

さくらサーバーへの自動デプロイは、初期段階ではCIに組み込まなくて構いません。

---

# フェーズ10: テスト

最低限、以下のテストを用意してください。

- トップまたはダッシュボード表示
- ログイン画面
- 未認証アクセス制御
- 管理者アクセス
- 一般ユーザーの拒否
- ユーザー一覧
- ユーザー作成
- ユーザー更新
- バリデーション
- 権限チェック
- 監査ログ記録
- UIの主要テキスト
- 404
- 本番設定時にデバッグ情報が出ない設計

テストは実際に実行し、結果を記録してください。

---

# フェーズ11: ドキュメント

以下を作成または更新してください。

## ルート

- `README.md`
- `CLAUDE.md`
- `.env.example`

## docs

- `docs/architecture.md`
- `docs/coding-rule.md`
- `docs/ui-guide.md`
- `docs/security.md`
- `docs/testing.md`
- `docs/deployment.md`
- `docs/module-guide.md`
- `docs/generator-guide.md`
- `docs/server-environment.md`
- `docs/roadmap.md`
- `docs/audits/foundation-audit.md`
- `docs/plans/framework-foundation-plan.md`

## prompts

- `prompts/new-module.md`
- `prompts/code-review.md`
- `prompts/deployment.md`

ドキュメントは、Claude Codeだけでなく人間の開発者も理解できる内容にしてください。

---

# フェーズ12: さくらサーバーへの適合確認

今回の主目的は基盤構築です。

本番公開を強行せず、まずサーバー適合性を確認してください。

## 接続

SSH configのエイリアス:

```text
buildone-sakura
```

接続例:

```bash
ssh buildone-sakura
```

SSH秘密鍵は既存のSSH configから解決される前提です。

秘密鍵の内容やパスフレーズを確認・表示しないでください。

## サーバー調査

接続できる場合は以下を確認してください。

- PHPバージョン
- Composer
- Git
- Node.js
- npm
- ホームディレクトリ
- `~/www`
- 公開ディレクトリ
- ディスク容量
- PHP拡張
- SQLite可否
- MySQL利用可否
- Laravelの `storage` と `bootstrap/cache` の権限
- cron利用可否
- queue運用可能性
- symlink可否

結果を `docs/server-environment.md` に保存してください。

## デプロイ方針

初期ドメイン:

`https://buildone.sakura.ne.jp`

想定配置:

```text
~/www/buildone
```

想定公開ディレクトリ:

```text
~/www/buildone/public
```

公開フォルダ変更ができない場合は、安全な代替案を設計してください。

Laravelプロジェクト全体を公開ディレクトリへ置かないでください。

初回デプロイでは `rsync --delete` を使用しないでください。

---

# フェーズ13: Git

## 推奨リポジトリ名

`buildone-framework`

## ブランチ

- `main`
- `develop`
- `feature/*`

小規模な初期運用では `main` + feature branchでも構いません。過剰なGit Flowを強制しないでください。

## コミット形式

- `feat:`
- `fix:`
- `refactor:`
- `docs:`
- `test:`
- `chore:`

## 確認

- `.env` が除外されている
- 秘密情報がない
- `node_modules` が除外されている
- `vendor` の扱いが適切
- ログが除外されている
- IDE固有ファイルが除外されている

GitHubへのpushは、リモートURLと許可が確認できた場合のみ実行してください。

---

# フェーズ14: 完了条件

以下を満たしたら、BuildOne Framework基盤の初期構築完了とします。

- Laravelが正常起動
- BuildOneブランド設定済み
- 共通レイアウトがある
- UI Kitの主要部品がある
- Core構造がある
- Feature追加可能
- ユーザー管理の最小機能がある
- ロール・権限の最小機能がある
- メニュー基盤がある
- Dashboard Widget基盤がある
- 設定基盤がある
- 通知拡張ポイントがある
- 監査ログの最小機能がある
- CLI拡張の最小実装がある
- テストが通る
- 静的解析が通る
- Pintが通る
- npm buildが通る
- GitHub Actionsがある
- ドキュメントがある
- さくらサーバー適合性が記録されている
- 秘密情報がコミットされていない

---

# 最終報告形式

作業完了後、以下の順で報告してください。

1. 読み込んだ設計資料
2. 現状調査結果
3. 採用アーキテクチャ
4. 作成したディレクトリ
5. 実装したCore機能
6. 実装したUIコンポーネント
7. 認証・権限の実装内容
8. CLI・Generatorの実装内容
9. テスト結果
10. 静的解析結果
11. フロントエンドビルド結果
12. CI内容
13. ドキュメント一覧
14. サーバー適合性
15. 未実装項目
16. リスク
17. 次に作るべきもの
18. 変更ファイル一覧
19. 推奨コミットメッセージ
20. ロールバック方法

---

# 最初の実行指示

最初にコードを変更せず、以下を実行してください。

1. 関連する設計書と `CLAUDE.md` を読む
2. ローカル環境を調査する
3. 既存コードを調査する
4. `docs/audits/foundation-audit.md` を作る
5. `docs/plans/framework-foundation-plan.md` を作る
6. 採用アーキテクチャと初期マイルストーンを提示する
7. 安全に進められる場合は基盤実装を開始する

BuildOne Frameworkは長期的な資産です。

短期的にコード量を増やすことより、理解しやすさ、再利用性、テスト可能性、安全性、将来の変更容易性を優先してください。
