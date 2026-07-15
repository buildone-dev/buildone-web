<x-layouts.app title="問い合わせ">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '問い合わせ']]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">問い合わせ</h2>
    </x-slot:header>

    @if ($inquiries->isEmpty())
        <x-empty-state title="問い合わせはまだありません" />
    @else
        <div class="space-y-4">
            @foreach ($inquiries as $inquiry)
                <x-card>
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="font-bold text-ink">{{ $inquiry->company }}</p>
                            <p class="text-sm text-slate-500">{{ $inquiry->contact_name }}・{{ $inquiry->email }}@if ($inquiry->phone)・{{ $inquiry->phone }}@endif</p>
                        </div>
                        <time class="text-xs text-slate-400">{{ $inquiry->created_at?->format('Y-m-d H:i') }}</time>
                    </div>
                    <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold text-slate-500">現在困っている業務</dt>
                            <dd class="mt-1 whitespace-pre-line text-sm text-ink">{{ $inquiry->current_task }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-slate-500">改善したい内容</dt>
                            <dd class="mt-1 whitespace-pre-line text-sm text-ink">{{ $inquiry->improvement }}</dd>
                        </div>
                    </dl>
                    @if ($inquiry->chat_transcript)
                        <details class="mt-3">
                            <summary class="cursor-pointer text-xs font-semibold text-brand">添付されたチャット内容</summary>
                            <pre class="mt-2 max-h-48 overflow-auto whitespace-pre-wrap rounded-lg bg-surface p-3 text-xs text-slate-600">{{ $inquiry->chat_transcript }}</pre>
                        </details>
                    @endif

                    @if ($inquiry->generated_prompt)
                        <div class="mt-4 rounded-xl border border-brand/20 bg-brand/[0.03] p-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-semibold text-brand">開発着手プロンプト（AIに渡してすぐ着手）</p>
                                <button type="button" data-copy="prompt-{{ $inquiry->id }}"
                                    class="rounded-lg border border-slate-300 bg-white px-2.5 py-1 text-xs font-semibold text-ink hover:border-brand hover:text-brand">コピー</button>
                            </div>
                            <pre id="prompt-{{ $inquiry->id }}" class="mt-2 max-h-64 overflow-auto whitespace-pre-wrap rounded-lg bg-white p-3 text-xs text-slate-700 ring-1 ring-slate-200">{{ $inquiry->generated_prompt }}</pre>
                        </div>
                    @endif
                </x-card>
            @endforeach
        </div>
        <x-pagination :paginator="$inquiries" />
    @endif
</x-layouts.app>
