<x-layouts.app title="日報">
    <x-slot:header>
        <x-breadcrumb :items="[
            ['label' => 'ダッシュボード', 'href' => route('dashboard')],
            ['label' => '日報', 'href' => route('report.index')],
            ['label' => $report->report_date->format('Y-m-d')],
        ]" />
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-2xl font-bold tracking-tight text-ink">{{ $report->report_date->format('Y年n月j日') }} の日報</h2>
            @can(\App\Core\Authorization\Permission::ReportManage->value)
                <x-link-button :href="route('report.edit', $report)" variant="secondary" size="sm">編集</x-link-button>
            @endcan
        </div>
        <p class="mt-1 text-sm text-slate-500">作成者: {{ $report->author?->name ?? '—' }}</p>
    </x-slot:header>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif
    @if (session('summary_error'))
        <x-alert variant="warning" class="mb-4">{{ session('summary_error') }}</x-alert>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <x-card title="本文">
            <div class="whitespace-pre-line text-sm leading-relaxed text-ink">{{ $report->body }}</div>
        </x-card>

        <x-card>
            <x-slot:actions>
                @can(\App\Core\Authorization\Permission::ReportManage->value)
                    <form method="POST" action="{{ route('report.summarize', $report) }}">
                        @csrf
                        <x-button type="submit" size="sm">{{ $report->summary ? 'AI要約を再生成' : 'AI要約を生成' }}</x-button>
                    </form>
                @endcan
            </x-slot:actions>
            <x-slot:title>AI要約</x-slot:title>

            @if ($report->summary)
                <div class="whitespace-pre-line rounded-xl bg-brand/[0.04] p-4 text-sm leading-relaxed text-ink ring-1 ring-brand/15">{{ $report->summary }}</div>
            @else
                <x-empty-state title="まだ要約がありません" description="「AI要約を生成」で本文を自動要約します（要APIキー）。" />
            @endif
        </x-card>
    </div>
</x-layouts.app>
