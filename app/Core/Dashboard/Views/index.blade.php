<x-layouts.app title="ダッシュボード">
    <x-slot:header>
        <h2 class="text-2xl font-bold tracking-tight text-ink">ダッシュボード</h2>
        <p class="mt-1 text-sm text-slate-500">BuildOne Framework 管理画面</p>
    </x-slot:header>

    {{-- 各業務モジュールのサマリー（権限のあるものだけ表示） --}}
    @if (! empty($widgets))
        <h3 class="mb-3 text-sm font-semibold uppercase tracking-widest text-brand">業務サマリー</h3>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($widgets as $widget)
                @if ($widget['url'])
                    <a href="{{ $widget['url'] }}" class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-brand hover:shadow-sm">
                        <p class="text-sm font-medium text-slate-500">{{ $widget['label'] }}</p>
                        <p class="mt-2 text-2xl font-bold text-ink">{{ $widget['value'] }}</p>
                    </a>
                @else
                    <x-stat-card :label="$widget['label']" :value="$widget['value']" />
                @endif
            @endforeach
        </div>
    @endif

    {{-- システム統計 --}}
    <h3 class="mb-3 mt-8 text-sm font-semibold uppercase tracking-widest text-slate-400">システム</h3>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="ユーザー数" :value="$userCount" />
        <x-stat-card label="有効ユーザー" :value="$activeCount" />
        <x-stat-card label="ロール数" :value="$roleCount" />
        <x-stat-card label="監査ログ" :value="$auditCount" />
    </div>

    <x-card title="最近のアクティビティ" class="mt-8">
        @forelse ($recent as $log)
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 py-2.5 last:border-0">
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-ink">{{ $log->action }}</p>
                    <p class="truncate text-xs text-slate-500">{{ $log->actor?->name ?? 'システム' }}</p>
                </div>
                <time class="shrink-0 text-xs text-slate-400">{{ $log->occurred_at?->format('Y-m-d H:i') }}</time>
            </div>
        @empty
            <x-empty-state title="まだアクティビティがありません" description="ユーザーを操作すると監査ログに記録されます。" />
        @endforelse
    </x-card>
</x-layouts.app>
