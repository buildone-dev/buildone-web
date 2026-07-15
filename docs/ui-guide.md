# UI ガイド（BuildOne Framework）

## デザイン方針

Apple / Stripe / Linear / Notion のような、シンプルで高級感のある BtoB UI。余白を広く取り、日本語の可読性を優先し、過度な装飾を避ける。

## ブランドカラー

Tailwind のテーマトークンとして `resources/css/app.css` に定義済み。

| トークン | 値 | 用途 |
|---|---|---|
| `brand` | `#2563EB` | メイン（ボタン・リンク・強調） |
| `brand-dark` | `#1D4ED8` | ホバー |
| `ink` | `#0F172A` | 本文・見出し・濃紺の面 |
| `accent` | `#22C55E` | アクセント（成功・ポジティブ） |
| `surface` | `#F8FAFC` | 背景 |

使用例: `class="bg-brand text-white"` / `class="text-ink"` / `class="bg-surface"`。

## フォント

Inter 相当 → 日本語システムフォント（Hiragino / Noto Sans JP / Yu Gothic / Meiryo）のフォールバック。外部フォントに依存せず、読み込めなくても崩れない（`resources/css/app.css` の `--font-sans`）。

## アクセシビリティ

- `lang="ja"`、適切な見出し階層（h1→h3）、`alt` 属性、キーボード操作対応、`:focus-visible` の明示。
- `prefers-reduced-motion` を尊重（アニメーションを抑制）。
- コントラスト比を確保（ブランドカラーは白背景で AA 準拠）。

## UI Kit（v2.5 予定）

v2.0設計書の共通コンポーネントを Blade コンポーネント（`resources/views/components/` または各モジュール共通）として提供予定:

Button / Card / Table / Modal / Form / Wizard / Chart / Timeline / Toast / EmptyState / Skeleton

現状はトップページLP（`resources/views/`）が実装済みの参照デザイン。UI Kit 化まではこのマークアップとブランドトークンを踏襲する。
