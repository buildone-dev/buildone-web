@if (setting_bool('chat_enabled', (bool) config('buildone.chat.enabled')))
    <div id="chat-widget" data-greeting="{{ config('buildone.chat.greeting') }}" data-endpoint="{{ route('chat.message') }}">
        {{-- ランチャー --}}
        <button id="chat-launcher" type="button" aria-label="AIアシスタントに相談する"
            class="fixed bottom-4 right-4 z-40 inline-flex h-14 w-14 items-center justify-center rounded-full bg-brand text-white shadow-lg transition hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
        </button>

        {{-- パネル --}}
        <div id="chat-panel"
            class="fixed bottom-4 right-4 z-40 hidden w-[calc(100vw-2rem)] max-w-sm overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl sm:bottom-24">
            <div class="flex items-center justify-between gap-2 bg-ink px-4 py-3 text-white">
                <div class="flex items-center gap-2">
                    <x-logo :size="22" :show-text="false" />
                    <span class="text-sm font-semibold">AIアシスタント</span>
                </div>
                <button id="chat-close" type="button" aria-label="閉じる" class="rounded p-1 text-slate-300 hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div id="chat-messages" class="flex h-80 flex-col gap-3 overflow-y-auto bg-surface p-4 text-sm" aria-live="polite"></div>

            <form id="chat-form" class="flex items-center gap-2 border-t border-slate-200 p-3">
                <input id="chat-input" type="text" autocomplete="off" maxlength="2000"
                    class="min-w-0 flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30"
                    placeholder="業務のお悩みを入力…" />
                <button type="submit" aria-label="送信"
                    class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand text-white hover:bg-brand-dark">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                </button>
            </form>
        </div>
    </div>
@endif
