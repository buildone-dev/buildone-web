@php($maxMinutes = collect($rows)->max('minutes') ?: 1)

<x-layouts.app title="月次勤怠">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '勤怠', 'href' => route('time.index')], ['label' => '月次集計']]" />
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-2xl font-bold tracking-tight text-ink">月次勤怠集計</h2>
            <form method="GET" class="flex items-center gap-2">
                <input type="month" name="month" value="{{ $month }}"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30" />
                <x-button type="submit" size="sm">表示</x-button>
            </form>
        </div>
    </x-slot:header>

    <div class="grid gap-4 sm:grid-cols-2">
        <x-stat-card label="対象月" :value="$month" />
        <x-stat-card label="合計勤務時間" :value="intdiv($totalMinutes, 60).'時間'.($totalMinutes % 60).'分'" />
    </div>

    <x-card title="従業員別の勤務時間" class="mt-6">
        @if (empty($rows))
            <x-empty-state title="この月の勤怠記録がありません" />
        @else
            <div class="space-y-3">
                @foreach ($rows as $row)
                    <div>
                        <div class="mb-1 flex items-baseline justify-between gap-3 text-sm">
                            <span class="font-medium text-ink">{{ $row['name'] }}</span>
                            <span class="text-slate-500">
                                <span class="font-semibold text-ink">{{ intdiv($row['minutes'], 60) }}</span>時間{{ $row['minutes'] % 60 }}分
                                <span class="ml-2 text-xs text-slate-400">/ {{ $row['days'] }}日</span>
                            </span>
                        </div>
                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-brand" style="width: {{ round($row['minutes'] / $maxMinutes * 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>
</x-layouts.app>
