<x-layouts.app title="予約">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '予約']]" />
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-2xl font-bold tracking-tight text-ink">予約</h2>
            <div class="flex gap-2">
                <x-link-button :href="route('reserve.export')" variant="secondary" size="sm">CSV出力</x-link-button>
                @can(\App\Core\Authorization\Permission::ReserveManage->value)
                    <x-link-button :href="route('reserve.create')" size="sm">予約を登録</x-link-button>
                @endcan
            </div>
        </div>
    </x-slot:header>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat-card label="本日の予約" :value="$todayCount" />
        <x-stat-card label="未確定" :value="$pendingCount" />
        <x-stat-card label="今後の予約" :value="$upcomingCount" />
    </div>

    <x-card title="今後の予約" class="mt-6">
        @if ($reservations->isEmpty())
            <x-empty-state title="今後の予約はありません" description="予約を登録してください。" />
        @else
            <x-table>
                <x-slot:head>
                    <th class="px-4 py-3">日時</th>
                    <th class="px-4 py-3">顧客</th>
                    <th class="px-4 py-3">人数</th>
                    <th class="px-4 py-3">状態</th>
                    <th class="px-4 py-3 text-right">操作</th>
                </x-slot:head>
                @foreach ($reservations as $reservation)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 font-medium text-ink">{{ $reservation->reserved_at->format('n/j H:i') }}</td>
                        <td class="px-4 py-3">
                            {{ $reservation->customer_name }}
                            @if ($reservation->phone)<span class="block text-xs text-slate-400">{{ $reservation->phone }}</span>@endif
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $reservation->party_size }}名</td>
                        <td class="px-4 py-3"><x-badge :variant="$reservation->statusVariant()">{{ $reservation->statusLabel() }}</x-badge></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                @can(\App\Core\Authorization\Permission::ReserveManage->value)
                                    @if ($reservation->status !== \App\Features\Reserve\Models\Reservation::CONFIRMED)
                                        <form method="POST" action="{{ route('reserve.confirm', $reservation) }}">
                                            @csrf @method('PATCH')
                                            <x-button type="submit" variant="secondary" size="sm">確定</x-button>
                                        </form>
                                    @endif
                                    <x-link-button :href="route('reserve.edit', $reservation)" variant="ghost" size="sm">編集</x-link-button>
                                    @if ($reservation->status !== \App\Features\Reserve\Models\Reservation::CANCELLED)
                                        <form method="POST" action="{{ route('reserve.cancel', $reservation) }}" onsubmit="return confirm('この予約をキャンセルしますか？');">
                                            @csrf @method('PATCH')
                                            <x-button type="submit" variant="ghost" size="sm">取消</x-button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
            <x-pagination :paginator="$reservations" />
        @endif
    </x-card>
</x-layouts.app>
