<?php

declare(strict_types=1);

namespace App\Features\Contact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'current_task' => ['required', 'string', 'max:5000'],
            'improvement' => ['required', 'string', 'max:5000'],
            'consent' => ['accepted'],
            'attach_chat' => ['sometimes', 'boolean'],
            'chat_transcript' => ['nullable', 'string', 'max:20000'],
            'conversation_id' => ['nullable', 'uuid'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'consent.accepted' => '個人情報の取り扱いへの同意が必要です。',
        ];
    }
}
