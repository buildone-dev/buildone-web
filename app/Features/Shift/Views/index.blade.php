<x-layouts.app title="シフト">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => 'シフト']]" />
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-2xl font-bold tracking-tight text-ink">シフト</h2>
            <div class="flex gap-2">
                <x-link-button :href="route('shift.export')" variant="secondary" size="sm">CSV出力</x-link-button>
                @can(\App\Core\Authorization\Permission::ShiftManage->value)
                    <x-link-button :href="route('shift.create')" size="sm">シフトを登録</x-link-button>
                @endcan
            </div>
        </div>
    </x-slot:header>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat-card label="本日のシフト" :value="$todayCount" />
        <x-stat-card label="今週のシフト" :value="$weekCount" />
        <x-stat-card label="登録従業員" :value="$employeeCount" />
    </div>

    <x-card title="今後のシフト" class="mt-6">
        @if ($shifts->isEmpty())
            <x-empty-state title="今後のシフトはありません" description="シフトを登録してください。" />
        @else
            <x-table>
                <x-slot:head>
                    <th class="px-4 py-3">日付</th>
                    <th class="px-4 py-3">従業員</th>
                    <th class="px-4 py-3">時間帯</th>
                    <th class="px-4 py-3">メモ</th>
                    <th class="px-4 py-3 text-right">操作</th>
                </x-slot:head>
                @foreach ($shifts as $shift)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 font-medium text-ink">{{ $shift->work_date->format('n/j') }}<span class="ml-1 text-xs text-slate-400">({{ ['日', '月', '火', '水', '木', '金', '土'][$shift->work_date->dayOfWeek] }})</span></td>
                        <td class="px-4 py-3">{{ $shift->employee->name }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $shift->timeRange() }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $shift->note }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                @can(\App\Core\Authorization\Permission::ShiftManage->value)
                                    <x-link-button :href="route('shift.edit', $shift)" variant="secondary" size="sm">編集</x-link-button>
                                    <form method="POST" action="{{ route('shift.destroy', $shift) }}" onsubmit="return confirm('このシフトを削除しますか？');">
                                        @csrf @method('DELETE')
                                        <x-button type="submit" variant="ghost" size="sm">削除</x-button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
            <x-pagination :paginator="$shifts" />
        @endif
    </x-card>
</x-layouts.app>
