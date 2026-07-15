<?php

declare(strict_types=1);

namespace App\Core\Settings\Http\Controllers;

use App\Core\Audit\AuditLogger;
use App\Core\Authorization\Permission;
use App\Core\Settings\Http\Requests\UpdateSettingsRequest;
use App\Core\Settings\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class SettingsController extends Controller
{
    /**
     * 管理画面から編集できる設定キー。
     */
    private const KEYS = [
        'youtube_url',
        'youtube_video_url',
        'contact_notify_email',
        'contact_url',
        'contact_email',
    ];

    public function __construct(
        private readonly Settings $settings,
        private readonly AuditLogger $audit,
    ) {}

    public function edit(): View
    {
        Gate::authorize(Permission::SettingsManage->value);

        return view('settings::edit', [
            'settings' => $this->settings,
        ]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        foreach (self::KEYS as $key) {
            $this->settings->set($key, $data[$key] ?? null);
        }

        $this->settings->set('chat_enabled', $request->boolean('chat_enabled') ? '1' : '0');

        $this->audit->log('settings.updated');

        return redirect()->route('settings.edit')->with('status', '設定を保存しました。');
    }
}
