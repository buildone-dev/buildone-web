<?php

declare(strict_types=1);

namespace App\Core\Audit;

use App\Core\Audit\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * 操作を監査ログに記録する。機密情報（パスワード等）は metadata に含めないこと。
 */
final class AuditLogger
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function log(string $action, ?Model $subject = null, array $metadata = []): AuditLog
    {
        return AuditLog::query()->create([
            'actor_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject !== null ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'metadata' => $metadata !== [] ? $metadata : null,
            'ip' => request()->ip(),
            'user_agent' => mb_substr((string) request()->userAgent(), 0, 512),
            'occurred_at' => now(),
        ]);
    }
}
