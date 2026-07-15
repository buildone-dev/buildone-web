<x-layouts.app title="入退室状況">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '入退室状況']]" />
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-2xl font-bold tracking-tight text-ink">入退室状況</h2>
            <div class="flex gap-2">
                <x-link-button :href="route('entry.kiosk')" variant="secondary" size="sm">打刻画面へ</x-link-button>
                <x-link-button :href="route('entry.export')" size="sm">CSV出力</x-link-button>
            </div>
        </div>
    </x-slot:header>

    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat-card label="本日の入室" :value="$todayIn" />
        <x-stat-card label="現在の在室" :value="$presentCount" />
        <x-stat-card label="登録利用者" :value="$memberCount" />
    </div>

    <x-card title="入退室履歴" class="mt-6">
        @if ($logs->isEmpty())
            <x-empty-state title="入退室履歴がありません" description="打刻画面から記録を始めてください。" />
        @else
            <x-table>
                <x-slot:head>
                    <th class="px-4 py-3">利用者</th>
                    <th class="px-4 py-3">区分</th>
                    <th class="px-4 py-3">日時</th>
                    <th class="px-4 py-3">方法</th>
                </x-slot:head>
                @foreach ($logs as $log)
                    <tr>
                        <td class="px-4 py-3 font-medium text-ink">{{ $log->member->name }}</td>
                        <td class="px-4 py-3"><x-badge :variant="$log->isEntry() ? 'success' : 'brand'">{{ $log->isEntry() ? '入室' : '退室' }}</x-badge></td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $log->recorded_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-4 py-3 text-slate-400">{{ $log->method }}</td>
                    </tr>
                @endforeach
            </x-table>
            <x-pagination :paginator="$logs" />
        @endif
    </x-card>
</x-layouts.app>
