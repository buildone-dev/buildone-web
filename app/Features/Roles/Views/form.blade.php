@php($isEdit = $role->exists)
@php($isSuperAdmin = $role->name === \App\Core\Authorization\Models\Role::SUPER_ADMIN)
@php($current = old('permissions', $role->permissions ?? []))

<x-layouts.app :title="$isEdit ? 'ロール編集' : 'ロール作成'">
    <x-slot:header>
        <x-breadcrumb :items="[
            ['label' => 'ダッシュボード', 'href' => route('dashboard')],
            ['label' => 'ロール・権限', 'href' => route('roles.index')],
            ['label' => $isEdit ? '編集' : '作成'],
        ]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">{{ $isEdit ? 'ロール編集' : 'ロール作成' }}</h2>
    </x-slot:header>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ $isEdit ? route('roles.update', $role) : route('roles.store') }}" class="space-y-5">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <x-form-field label="表示名" for="label" :required="true" :error="$errors->get('label')">
                <x-input id="label" name="label" value="{{ old('label', $role->label) }}" :error="$errors->has('label')" required />
            </x-form-field>

            @unless ($isEdit)
                <x-form-field label="識別子（半角英数・ハイフン）" for="name" :required="true" hint="作成後は変更できません" :error="$errors->get('name')">
                    <x-input id="name" name="name" value="{{ old('name') }}" :error="$errors->has('name')" required placeholder="例: manager" />
                </x-form-field>
            @endunless

            <div>
                <p class="mb-2 text-sm font-medium text-ink">権限</p>
                @if ($isSuperAdmin)
                    <x-alert variant="info">スーパー管理者は常にすべての権限を持ちます（変更不可）。</x-alert>
                @else
                    <div class="grid gap-2 rounded-xl border border-slate-200 p-4 sm:grid-cols-2">
                        @foreach ($permissions as $permission)
                            <x-checkbox name="permissions[]" value="{{ $permission->value }}"
                                :label="$permission->label()" :checked="in_array($permission->value, $current, true)" />
                        @endforeach
                    </div>
                    <x-validation-error :messages="$errors->get('permissions')" />
                @endif
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">{{ $isEdit ? '更新' : '作成' }}</x-button>
                <x-link-button :href="route('roles.index')" variant="ghost">キャンセル</x-link-button>
            </div>
        </form>
    </x-card>
</x-layouts.app>
