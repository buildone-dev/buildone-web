<x-layouts.app title="勤怠一覧">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '勤怠一覧']]" />
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-2xl font-bold tracking-tight text-ink">勤怠一覧</h2>
            <div class="flex gap-2">
                <x-link-button :href="route('time.monthly')" variant="secondary" size="sm">月次集計</x-link-button>
                <x-link-button :href="route('time.kiosk')" variant="secondary" size="sm">打刻画面へ</x-link-button>
                <x-link-button :href="route('time.export')" size="sm">CSV出力</x-link-button>
            </div>
        </div>
    </x-slot:header>

    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat-card label="現在の出勤者" :value="$workingCount" />
        <x-stat-card label="本日の勤務合計" :value="$todayHours" />
        <x-stat-card label="登録従業員" :value="$employeeCount" />
    </div>

    <x-card title="勤怠記録" class="mt-6">
        @if ($records->isEmpty())
            <x-empty-state title="勤怠記録がありません" description="打刻画面から記録を始めてください。" />
        @else
            <x-table>
                <x-slot:head>
                    <th class="px-4 py-3">従業員</th>
                    <th class="px-4 py-3">出勤</th>
                    <th class="px-4 py-3">退勤</th>
                    <th class="px-4 py-3">勤務時間</th>
                </x-slot:head>
                @foreach ($records as $record)
                    <tr>
                        <td class="px-4 py-3 font-medium text-ink">{{ $record->employee->name }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $record->clock_in->format('Y-m-d H:i') }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $record->clock_out?->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($record->clock_out === null)
                                <x-badge variant="success">勤務中</x-badge>
                            @else
                                {{ $record->workedLabel() }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-table>
            <x-pagination :paginator="$records" />
        @endif
    </x-card>
</x-layouts.app>
