<x-layouts.app title="日報">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '日報']]" />
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-2xl font-bold tracking-tight text-ink">日報</h2>
            <div class="flex gap-2">
                <x-link-button :href="route('report.export')" variant="secondary" size="sm">CSV出力</x-link-button>
                @can(\App\Core\Authorization\Permission::ReportManage->value)
                    <x-link-button :href="route('report.create')" size="sm">日報を作成</x-link-button>
                @endcan
            </div>
        </div>
    </x-slot:header>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat-card label="今月の日報" :value="$monthCount" />
        <x-stat-card label="AI要約済み" :value="$summarizedCount" />
        <x-stat-card label="総数" :value="$totalCount" />
    </div>

    <x-card title="日報一覧" class="mt-6">
        @if ($reports->isEmpty())
            <x-empty-state title="日報がありません" description="日報を作成してください。" />
        @else
            <x-table>
                <x-slot:head>
                    <th class="px-4 py-3">日付</th>
                    <th class="px-4 py-3">作成者</th>
                    <th class="px-4 py-3">AI要約</th>
                    <th class="px-4 py-3 text-right">操作</th>
                </x-slot:head>
                @foreach ($reports as $report)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 font-medium text-ink">{{ $report->report_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ $report->author?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($report->summary)
                                <x-badge variant="success">要約済み</x-badge>
                            @else
                                <x-badge>未要約</x-badge>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <x-link-button :href="route('report.show', $report)" variant="secondary" size="sm">開く</x-link-button>
                        </td>
                    </tr>
                @endforeach
            </x-table>
            <x-pagination :paginator="$reports" />
        @endif
    </x-card>
</x-layouts.app>
