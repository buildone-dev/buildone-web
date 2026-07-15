<x-layouts.app title="監査ログ">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '監査ログ']]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">監査ログ</h2>
    </x-slot:header>

    @if ($logs->isEmpty())
        <x-empty-state title="監査ログがありません" description="ユーザー操作などが記録されます。" />
    @else
        <x-table>
            <x-slot:head>
                <th class="px-4 py-3">日時</th>
                <th class="px-4 py-3">操作者</th>
                <th class="px-4 py-3">アクション</th>
                <th class="px-4 py-3">対象</th>
                <th class="px-4 py-3">IP</th>
            </x-slot:head>
            @foreach ($logs as $log)
                <tr>
                    <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $log->occurred_at?->format('Y-m-d H:i:s') }}</td>
                    <td class="px-4 py-3">{{ $log->actor?->name ?? 'システム' }}</td>
                    <td class="px-4 py-3"><x-badge variant="brand">{{ $log->action }}</x-badge></td>
                    <td class="px-4 py-3 text-slate-500">{{ class_basename($log->subject_type ?? '') }}{{ $log->subject_id ? ' #'.$log->subject_id : '' }}</td>
                    <td class="px-4 py-3 text-slate-400">{{ $log->ip }}</td>
                </tr>
            @endforeach
        </x-table>
        <x-pagination :paginator="$logs" />
    @endif
</x-layouts.app>
