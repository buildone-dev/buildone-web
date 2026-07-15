# **BuildOne Framework v2.0 設計書**

**目的**  
BuildOne Frameworkは、AIと人が協調して業務システムを高速開発するための再利用可能なプラットフォームである。100種類以上の業務システムを共通基盤で構築できることを目標とする。

## **設計理念**

* Business First（業務価値を最優先）  
* AI Native（AI協調開発を前提）  
* Feature First（機能単位の独立性）  
* Security by Default  
* Test First  
* Documentation as Code

## **レイヤー構成**

* Presentation (Blade/UI)  
* Application (UseCase/Action)  
* Domain (Entity/Service/Rule)  
* Infrastructure (DB/API/File)

## **Coreモジュール**

* 認証・認可  
* ユーザー管理  
* ロール/権限  
* 監査ログ  
* 通知  
* 設定  
* テーマ  
* ダッシュボード  
* ファイル管理

## **UI Kit**

* Button  
* Card  
* Table  
* Modal  
* Form  
* Wizard  
* Chart  
* Timeline  
* Toast  
* EmptyState  
* Skeleton

## **CLI**

* buildone:new-module  
* buildone:crud  
* buildone:deploy  
* buildone:test  
* buildone:docs

## **AI Engine**

* 要件整理  
* CRUD生成  
* テスト生成  
* ドキュメント生成  
* レビュー支援  
* リファクタリング提案

## **品質基準**

* Laravel Pint  
* PHPStan  
* Pest  
* Feature Test  
* GitHub Actions  
* Dependabot

## **ディレクトリ構成**

* app/Core  
* app/Features  
* app/Domain  
* app/Infrastructure  
* resources  
* docs  
* prompts  
* tests

## **モジュールロードマップ**

* Entry  
* Time  
* Shift  
* Reserve  
* Report  
* Stock  
* CRM  
* Invoice  
* Customer  
* Workflow

## **SaaS対応**

* マルチテナント  
* サブスクリプション  
* プラグイン  
* API  
* Webhook  
* 監査

## **Claude Codeルール**

* 既存設計を尊重する  
* Coreを汚さない  
* 共通コンポーネントを再利用  
* README/docs更新  
* 危険操作前は確認

## **ロードマップ**

* v2.0: Framework基盤完成  
* v2.5: CRUD Generator  
* v3.0: SaaS化  
* v4.0: Marketplace

## **Claude Codeへの基本指示**

この設計書を最優先ルールとして扱い、BuildOne Frameworkを拡張可能・保守性重視で実装すること。