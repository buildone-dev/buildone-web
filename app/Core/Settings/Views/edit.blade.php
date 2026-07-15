<x-layouts.app title="設定">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '設定']]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">設定</h2>
        <p class="mt-1 text-sm text-slate-500">ここで保存した内容は、公開サイト・チャットに即時反映されます（.env の編集は不要）。</p>
    </x-slot:header>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" class="max-w-2xl space-y-6">
        @csrf @method('PUT')

        <x-card title="YouTube">
            <div class="space-y-5">
                <x-form-field label="チャンネルURL" for="youtube_url" hint="設定すると「YouTubeを見る」等がリンクします" :error="$errors->get('youtube_url')">
                    <x-input id="youtube_url" name="youtube_url" type="url" value="{{ old('youtube_url', $settings->get('youtube_url')) }}" :error="$errors->has('youtube_url')" placeholder="https://www.youtube.com/@buildonejp" />
                </x-form-field>
                <x-form-field label="トップ掲載動画の埋め込みURL" for="youtube_video_url" hint="未設定なら「近日公開」表示" :error="$errors->get('youtube_video_url')">
                    <x-input id="youtube_video_url" name="youtube_video_url" type="url" value="{{ old('youtube_video_url', $settings->get('youtube_video_url')) }}" :error="$errors->has('youtube_video_url')" placeholder="https://www.youtube.com/embed/..." />
                </x-form-field>
            </div>
        </x-card>

        <x-card title="問い合わせ">
            <div class="space-y-5">
                <x-form-field label="通知先メール" for="contact_notify_email" hint="問い合わせ受信時にこのアドレスへ通知" :error="$errors->get('contact_notify_email')">
                    <x-input id="contact_notify_email" name="contact_notify_email" type="email" value="{{ old('contact_notify_email', $settings->get('contact_notify_email')) }}" :error="$errors->has('contact_notify_email')" placeholder="info@buildone.sakura.ne.jp" />
                </x-form-field>
                <x-form-field label="外部フォームURL（任意）" for="contact_url" :error="$errors->get('contact_url')">
                    <x-input id="contact_url" name="contact_url" type="url" value="{{ old('contact_url', $settings->get('contact_url')) }}" :error="$errors->has('contact_url')" />
                </x-form-field>
                <x-form-field label="問い合わせメール（任意）" for="contact_email" :error="$errors->get('contact_email')">
                    <x-input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings->get('contact_email')) }}" :error="$errors->has('contact_email')" />
                </x-form-field>
            </div>
        </x-card>

        <x-card title="AIチャット">
            <x-checkbox name="chat_enabled" value="1" label="公開サイトにAIチャットを表示する" :checked="old('chat_enabled', $settings->bool('chat_enabled', (bool) config('buildone.chat.enabled')))" />
        </x-card>

        <x-button type="submit">設定を保存</x-button>
    </form>
</x-layouts.app>
