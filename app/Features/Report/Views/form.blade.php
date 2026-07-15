@php($isEdit = $report->exists)

<x-layouts.app :title="$isEdit ? '日報編集' : '日報作成'">
    <x-slot:header>
        <x-breadcrumb :items="[
            ['label' => 'ダッシュボード', 'href' => route('dashboard')],
            ['label' => '日報', 'href' => route('report.index')],
            ['label' => $isEdit ? '編集' : '作成'],
        ]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">{{ $isEdit ? '日報編集' : '日報作成' }}</h2>
    </x-slot:header>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ $isEdit ? route('report.update', $report) : route('report.store') }}" class="space-y-5">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <x-form-field label="日付" for="report_date" :required="true" :error="$errors->get('report_date')">
                <x-input id="report_date" name="report_date" type="date"
                    value="{{ old('report_date', optional($report->report_date)->format('Y-m-d')) }}" :error="$errors->has('report_date')" required />
            </x-form-field>

            <x-form-field label="本文" for="body" :required="true" hint="箇条書きでも文章でも構いません" :error="$errors->get('body')">
                <x-textarea id="body" name="body" rows="10" :error="$errors->has('body')" required placeholder="・訪問／連絡した内容&#10;・進捗や気づき&#10;・翌日の予定">{{ old('body', $report->body) }}</x-textarea>
            </x-form-field>

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">{{ $isEdit ? '更新' : '作成' }}</x-button>
                <x-link-button :href="route('report.index')" variant="ghost">キャンセル</x-link-button>
            </div>
        </form>
    </x-card>
</x-layouts.app>
