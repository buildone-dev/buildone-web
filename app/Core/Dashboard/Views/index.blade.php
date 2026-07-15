<x-layouts.app title="ダッシュボード">
    <x-slot:header>
        <h2 class="text-2xl font-bold tracking-tight text-ink">ダッシュボード</h2>
        <p class="mt-1 text-sm text-slate-500">BuildOne Framework 管理画面</p>
    </x-slot:header>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="ユーザー数" :value="$userCount" />
        <x-stat-card label="有効ユーザー" :value="$activeCount" />
        <x-stat-card label="ロール数" :value="$roleCount" />
        <x-stat-card label="監査ログ" :value="$auditCount" />
    </div>

    <x-card title="最近のアクティビティ" class="mt-6">
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
