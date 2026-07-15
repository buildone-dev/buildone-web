<?php

declare(strict_types=1);

namespace App\Features\Report\Services;

use App\Features\Chat\Contracts\ChatResponder;
use Throwable;

/**
 * 日報本文をAI（既存のChatResponder=Anthropic）で要約する。
 * APIキー未設定や失敗時は null を返し、要約なしで運用を継続する。
 */
final class ReportSummarizer
{
    public function __construct(private readonly ChatResponder $responder) {}

    public function summarize(string $body): ?string
    {
        try {
            $summary = $this->responder->reply([
                [
                    'role' => 'user',
                    'content' => "次の業務日報を、要点を3〜5個の箇条書きで簡潔にまとめてください。前置きは不要です。\n\n---\n".$body,
                ],
            ]);

            return trim($summary) !== '' ? $summary : null;
        } catch (Throwable) {
            return null;
        }
    }
}
