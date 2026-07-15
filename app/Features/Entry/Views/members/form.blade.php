@php($isEdit = $member->exists)

<x-layouts.app :title="$isEdit ? '利用者編集' : '利用者登録'">
    <x-slot:header>
        <x-breadcrumb :items="[
            ['label' => 'ダッシュボード', 'href' => route('dashboard')],
            ['label' => '利用者', 'href' => route('entry.members.index')],
            ['label' => $isEdit ? '編集' : '登録'],
        ]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">{{ $isEdit ? '利用者編集' : '利用者登録' }}</h2>
    </x-slot:header>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ $isEdit ? route('entry.members.update', $member) : route('entry.members.store') }}" class="space-y-5">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <x-form-field label="名前" for="name" :required="true" :error="$errors->get('name')">
                <x-input id="name" name="name" value="{{ old('name', $member->name) }}" :error="$errors->has('name')" required />
            </x-form-field>

            <x-form-field label="コード（QR / ICカード）" for="code" :required="true"
                hint="カードリーダーで読み取った値、またはQRの文字列" :error="$errors->get('code')">
                <x-input id="code" name="code" value="{{ old('code', $member->code) }}" :error="$errors->has('code')" required />
            </x-form-field>

            <x-checkbox name="is_active" value="1" label="有効" :checked="old('is_active', $member->is_active ?? true)" />

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">{{ $isEdit ? '更新' : '登録' }}</x-button>
                <x-link-button :href="route('entry.members.index')" variant="ghost">キャンセル</x-link-button>
            </div>
        </form>
    </x-card>
</x-layouts.app>
