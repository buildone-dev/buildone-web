<?php

declare(strict_types=1);

namespace App\Core\Settings\Http\Requests;

use App\Core\Authorization\Permission;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::SettingsManage->value) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'youtube_video_url' => ['nullable', 'url', 'max:255'],
            'contact_notify_email' => ['nullable', 'email', 'max:255'],
            'contact_url' => ['nullable', 'url', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'chat_enabled' => ['sometimes', 'boolean'],
        ];
    }
}
