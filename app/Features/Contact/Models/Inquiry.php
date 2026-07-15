<?php

declare(strict_types=1);

namespace App\Features\Contact\Models;

use App\Features\Contact\Database\Factories\InquiryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 問い合わせ。chat_transcript はユーザーが同意した場合のみ保存する。
 *
 * @property string $company
 * @property string $contact_name
 * @property string $email
 * @property string|null $phone
 * @property string $current_task
 * @property string $improvement
 * @property string|null $chat_transcript
 * @property string|null $conversation_id
 * @property string|null $generated_prompt
 * @property string $status
 */
class Inquiry extends Model
{
    /** @use HasFactory<InquiryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'company',
        'contact_name',
        'email',
        'phone',
        'current_task',
        'improvement',
        'chat_transcript',
        'conversation_id',
        'generated_prompt',
        'status',
    ];

    protected static function newFactory(): InquiryFactory
    {
        return InquiryFactory::new();
    }
}
