# コンテンツ追加・変更ガイド

トップページのコンテンツはデータベースを使わず、`config/buildone.php` の配列で管理している。
変更後は本番で `php artisan config:cache` の再実行が必要（`deploy/deploy.sh` が自動で行う）。

## 開発システムのカードを追加する

`config/buildone.php` の `systems` 配列に1要素足すだけ:

```php
[
    'name' => 'BuildOne Stock',      // システム名
    'description' => '在庫管理',      // 一行説明
    'status' => 'Coming Soon',       // バッジ表示（例: '第1弾・開発予定', '開発中', '公開中'）
    'featured' => false,             // true にすると強調枠（青系）になる
],
```

## 特徴・開発の流れを変更する

- `features` 配列: `icon`（sparkles / bolt / cog / camera / check / chat）+ `title` + `text`
- `process` 配列: `step`（番号）+ `title` + `text`

アイコンを増やす場合は `resources/views/home.blade.php` の `@switch($feature['icon'])` に SVG を追加する。

## YouTube・問い合わせ先を有効化する

サーバーの `~/buildone-web/.env` に設定して `php artisan config:cache`:

```
BUILDONE_YOUTUBE_URL=https://www.youtube.com/@buildonejp
BUILDONE_YOUTUBE_VIDEO_URL=https://www.youtube.com/embed/XXXXXXXX
CONTACT_URL=https://forms.example.com/...      # または CONTACT_EMAIL=info@...
```

- どちらも未設定の間は「準備中」「近日公開」の安全な表示になる（無効なリンクは出ない）
- `CONTACT_URL` と `CONTACT_EMAIL` が両方あると URL が優先される

## ページを追加する

1. `resources/views/` にビューを作成（`@extends('layouts.app')`）
2. `routes/web.php` に `Route::view()` を追加
3. `public/sitemap.xml` に URL を追記
4. `tests/Feature/PagesTest.php` に 200 応答のテストを追加

## OGP画像の差し替え

`public/ogp.svg` は仮画像。正式版は 1200x630 の PNG/JPG を `public/ogp.png` として置き、
`resources/views/layouts/app.blade.php` の `og:image` を差し替える
（SVGはSNSクローラーが解釈しないため、正式運用ではPNG/JPG推奨）。
