<?php

declare(strict_types=1);

namespace App\Features\Chat\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * サイトのチャットボットでやり取りされた1メッセージ。
 * すべての会話を保存し、リード分析や依頼時の開発プロンプト生成に用いる。
 *
 * @property string $conversation_id
 * @property string $role
 * @property string $content
 */
class ChatMessage extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'conversation_id',
        'role',
        'content',
    ];
}
