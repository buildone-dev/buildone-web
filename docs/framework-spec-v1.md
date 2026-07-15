# BuildOne Framework v1.0 設計書

## ビジョン

BuildOne
Frameworkは100種類以上の業務システムを高速開発するためのLaravelベースのフレームワークです。

## 設計原則

-   DRY
-   SOLID
-   Clean Architecture
-   Feature First
-   AIとの協調開発
-   テストファースト

## アーキテクチャ

Presentation→Application→Domain→Infrastructure

## Feature

-   Auth
-   Users
-   Dashboard
-   Entry
-   Time
-   Shift
-   Reserve
-   Report

## Core

認証、権限、UI、通知、監査ログ、設定、テーマ、メニュー

## UI

Apple・Stripe・Linear風 ブランドカラー: #2563EB #0F172A #22C55E

## 共通コンポーネント

Button/Card/Table/Modal/Input/Alert/Toast/Pagination

## CRUD Generator

php artisan buildone:crud {Module}

## AI Helper

SQL生成・CRUD生成・テスト生成・ドキュメント生成

## 品質

Laravel Pint / PHPStan / Pest / GitHub Actions

## デプロイ

さくらレンタルサーバー https://buildone.sakura.ne.jp

## ドキュメント

README.md CLAUDE.md docs/architecture.md docs/ui-guide.md
docs/deployment.md

## 開発フロー

要件定義→設計→実装→テスト→YouTube公開→改善

## ゴール

BuildOne
Frameworkを土台に1週間で業務システムを開発できる状態を実現する。
