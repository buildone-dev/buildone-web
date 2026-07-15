@php($isEdit = $shift->exists)

<x-layouts.app :title="$isEdit ? 'シフト編集' : 'シフト登録'">
    <x-slot:header>
        <x-breadcrumb :items="[
            ['label' => 'ダッシュボード', 'href' => route('dashboard')],
            ['label' => 'シフト', 'href' => route('shift.index')],
            ['label' => $isEdit ? '編集' : '登録'],
        ]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">{{ $isEdit ? 'シフト編集' : 'シフト登録' }}</h2>
    </x-slot:header>

    <x-card class="max-w-2xl">
        @if ($employees->isEmpty())
            <x-alert variant="warning">先に「勤怠 → 従業員」で従業員を登録してください。</x-alert>
        @else
            <form method="POST" action="{{ $isEdit ? route('shift.update', $shift) : route('shift.store') }}" class="space-y-5">
                @csrf
                @if ($isEdit) @method('PUT') @endif

                <x-form-field label="従業員" for="employee_id" :required="true" :error="$errors->get('employee_id')">
                    <x-select id="employee_id" name="employee_id">
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id', $shift->employee_id) == $employee->id)>{{ $employee->name }}</option>
                        @endforeach
                    </x-select>
                </x-form-field>

                <x-form-field label="日付" for="work_date" :required="true" :error="$errors->get('work_date')">
                    <x-input id="work_date" name="work_date" type="date"
                        value="{{ old('work_date', optional($shift->work_date)->format('Y-m-d')) }}" :error="$errors->has('work_date')" required />
                </x-form-field>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form-field label="開始時刻" for="start_time" :required="true" :error="$errors->get('start_time')">
                        <x-input id="start_time" name="start_time" type="time"
                            value="{{ old('start_time', $shift->start_time ?? '09:00') }}" :error="$errors->has('start_time')" required />
                    </x-form-field>
                    <x-form-field label="終了時刻" for="end_time" :required="true" :error="$errors->get('end_time')">
                        <x-input id="end_time" name="end_time" type="time"
                            value="{{ old('end_time', $shift->end_time ?? '18:00') }}" :error="$errors->has('end_time')" required />
                    </x-form-field>
                </div>

                <x-form-field label="メモ（任意）" for="note" :error="$errors->get('note')">
                    <x-input id="note" name="note" value="{{ old('note', $shift->note) }}" :error="$errors->has('note')" placeholder="例：レジ担当" />
                </x-form-field>

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit">{{ $isEdit ? '更新' : '登録' }}</x-button>
                    <x-link-button :href="route('shift.index')" variant="ghost">キャンセル</x-link-button>
                </div>
            </form>
        @endif
    </x-card>
</x-layouts.app>
