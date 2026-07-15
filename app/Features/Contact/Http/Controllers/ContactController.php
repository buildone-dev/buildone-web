<?php

declare(strict_types=1);

namespace App\Features\Contact\Http\Controllers;

use App\Features\Contact\Http\Requests\StoreInquiryRequest;
use App\Features\Contact\Models\Inquiry;
use App\Features\Contact\Services\PromptGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact::form');
    }

    public function store(StoreInquiryRequest $request, PromptGenerator $generator): RedirectResponse
    {
        $data = $request->safe()->only([
            'company', 'contact_name', 'email', 'phone', 'current_task', 'improvement',
        ]);

        // チャット内容はユーザーが同意（チェック）した場合のみ問い合わせに添付・保存する
        $data['chat_transcript'] = $request->boolean('attach_chat')
            ? $request->string('chat_transcript')->trim()->value()
            : null;

        $data['conversation_id'] = $request->filled('conversation_id')
            ? (string) $request->string('conversation_id')
            : null;

        $inquiry = Inquiry::query()->create($data);

        // 依頼内容から開発着手プロンプトを生成しておく（管理者がすぐ着手できるように）
        $inquiry->update(['generated_prompt' => $generator->forInquiry($inquiry)]);

        return redirect()->route('contact.show')->with('inquiry_sent', true);
    }
}
