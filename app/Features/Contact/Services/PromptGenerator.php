<?php

declare(strict_types=1);

namespace App\Features\Contact\Services;

use App\Features\Contact\Models\Inquiry;

/**
 * 問い合わせ内容から「開発着手プロンプト」を生成する。
 * 管理者がこれをそのままAI（Claude Code等）に渡し、すぐに開発へ取りかかれるようにする。
 */
final class PromptGenerator
{
    public function forInquiry(Inquiry $inquiry): string
    {
        $chat = trim((string) $inquiry->chat_transcript) !== ''
            ? $inquiry->chat_transcript
            : '（チャットでの相談内容はありません）';

        return <<<PROMPT
        # BuildOne 開発着手プロンプト

        あなたはBuildOne Frameworkに精通したシニアLaravelエンジニアです。
        以下のお客様の業務課題を解決する、最小限で実用的な業務システムを設計・実装してください。

        ## お客様
        - 会社・事業者名: {$inquiry->company}
        - ご担当者: {$inquiry->contact_name}

        ## 現在困っている業務
        {$inquiry->current_task}

        ## 改善したい内容
        {$inquiry->improvement}

        ## チャットでの相談内容
        {$chat}

        ## 実装方針
        - BuildOne Framework（Feature First / Laravel標準寄り）に沿って実装する。
        - まず `php artisan buildone:make-module {適切なモジュール名}` で雛形を作る。
        - 必要なモデル・マイグレーション・画面（UI Kit使用）・FormRequest・権限・テストを用意する。
        - 過剰設計を避け、最小限の機能から着手する。まず動くものを1つ作る。
        - `composer ci`（Pint / PHPStan / Pest）が緑になることを確認する。

        ## 最初の一歩
        1. 上記の課題を1〜2機能に分解し、モジュール名と主なフィールドを決める。
        2. `buildone:make-module` で雛形を生成し、モデルとマイグレーションを整える。
        3. 一覧・登録の最小画面とテストを作り、動作を確認する。
        PROMPT;
    }
}
