<?php

declare(strict_types=1);

namespace App\Features\Contact\Services;

use App\Features\Contact\Models\Inquiry;
use Illuminate\Support\Facades\Http;

/**
 * 問い合わせ受信をLINE Messaging APIでプッシュ通知する。
 * チャネルアクセストークン・送信先のどちらかが未設定なら何もしない。
 */
final class LineNotifier
{
    public function notify(Inquiry $inquiry): void
    {
        $token = config('services.line.channel_access_token');
        $to = config('services.line.notify_to');

        if (! is_string($token) || $token === '' || ! is_string($to) || $to === '') {
            return;
        }

        Http::withToken($token)
            ->throw()
            ->post('https://api.line.me/v2/bot/message/push', [
                'to' => $to,
                'messages' => [
                    ['type' => 'text', 'text' => $this->messageFor($inquiry)],
                ],
            ]);
    }

    private function messageFor(Inquiry $inquiry): string
    {
        return "【BuildOne】新しいお問い合わせ\n会社名: {$inquiry->company}\nご担当者: {$inquiry->contact_name}\n現在困っている業務: {$inquiry->current_task}";
    }
}
