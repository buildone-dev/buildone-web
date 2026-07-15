# UI ガイド（BuildOne Framework）

## デザイン方針

Apple / Stripe / Linear / Notion のような、シンプルで高級感のある BtoB UI。余白を広く、日本語の可読性を優先し、過度な装飾を避ける。モバイルファースト。

## ブランドカラー（`resources/css/app.css` のテーマトークン）

| トークン | 値 | 用途 |
|---|---|---|
| `brand` | `#2563EB` | メイン（ボタン・リンク・強調） |
| `brand-dark` | `#1D4ED8` | ホバー |
| `ink` | `#0F172A` | 本文・見出し・濃紺の面 |
| `accent` | `#22C55E` | アクセント（成功） |
| `surface` | `#F8FAFC` | 背景 |

例: `class="bg-brand text-white"` / `text-ink` / `bg-surface`。

## フォント

Inter 相当 → 日本語システムフォント（Hiragino / Noto Sans JP / Yu Gothic / Meiryo）のフォールバック。外部フォントに依存せず、読み込めなくても崩れない。

## アクセシビリティ

`lang="ja"` / 見出し階層 / `alt` / キーボード操作 / `:focus-visible` の明示 / `prefers-reduced-motion` 尊重 / コントラストAA。

## UI Kit（Blade Components）

`resources/views/components/` に配置。`/ui-kit`（非本番のみ）でプレビューできる。

### レイアウト
- `<x-layouts.app title="...">` — 管理画面シェル（サイドバー＋ヘッダー＋コンテンツ、`$header` スロット可）
- `<x-layouts.guest>` — ログイン等の中央寄せレイアウト

### 構造
- `<x-header :title="">` / `<x-sidebar />` / `<x-mobile-nav :items="[]" />`

### アクション
- `<x-button variant="primary|secondary|ghost|danger" size="sm|md|lg" :loading="" disabled>` 
- `<x-link-button href="" variant size>` — 見た目はボタンの `<a>`

### 表示
- `<x-card title="">`（`$actions` スロット可） / `<x-stat-card label value delta trend="up|down">`
- `<x-badge variant="neutral|brand|success|warning|danger">` / `<x-alert variant title>`
- `<x-table>`（`$head` スロット） / `<x-empty-state title description>`（`$action` スロット）
- `<x-loading>` / `<x-skeleton :lines="">` / `<x-pagination :paginator="">`
- `<x-breadcrumb :items="[['label'=>'','href'=>'']]" />`

### フォーム
- `<x-form-field label for required hint :error="[]">` — ラベル＋スロット＋エラーをまとめる
- `<x-input type error>` / `<x-textarea rows error>` / `<x-select error>` / `<x-checkbox label>` / `<x-radio label>`
- `<x-validation-error :messages="[]">`

### オーバーレイ（最小 vanilla JS。`resources/js/app.js`）
- `<x-modal id="m1" title size>` … `data-modal-open="m1"` で開き、`data-modal-close` / 背景クリック / Esc で閉じる（`$footer` スロット可）
- `<x-dropdown id="d1" align="right">`（`$trigger` スロット） … 外側クリックで閉じる
- トースト: `<x-toast />` を配置し、JS から `window.toast('保存しました', 'success')`

## 各コンポーネントの状態対応

disabled / loading / error / focus / キーボード操作 / スクリーンリーダー（`role`・`aria-*`）を考慮済み。ダークモードは未実装だが、色をトークン（`ink` / `surface` / `brand`）で参照しているため、`@media (prefers-color-scheme)` でトークンを差し替える拡張余地を残している。

## 外部JS方針

Alpine.js 等の外部UIライブラリは使わない。開閉・トーストは `resources/js/app.js` の最小 vanilla JS（データ属性ベース）で実装する。
