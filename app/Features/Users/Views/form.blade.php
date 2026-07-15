@php($isEdit = $user->exists)

<x-layouts.app :title="$isEdit ? 'ユーザー編集' : 'ユーザー作成'">
    <x-slot:header>
        <x-breadcrumb :items="[
            ['label' => 'ダッシュボード', 'href' => route('dashboard')],
            ['label' => 'ユーザー', 'href' => route('users.index')],
            ['label' => $isEdit ? '編集' : '新規作成'],
        ]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">{{ $isEdit ? 'ユーザー編集' : 'ユーザー作成' }}</h2>
    </x-slot:header>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}" class="space-y-5">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <x-form-field label="名前" for="name" :required="true" :error="$errors->get('name')">
                <x-input id="name" name="name" value="{{ old('name', $user->name) }}" :error="$errors->has('name')" required />
            </x-form-field>

            <x-form-field label="メールアドレス" for="email" :required="true" :error="$errors->get('email')">
                <x-input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" :error="$errors->has('email')" required />
            </x-form-field>

            <x-form-field label="ロール" for="role_id" :error="$errors->get('role_id')">
                <x-select id="role_id" name="role_id">
                    <option value="">（未割り当て）</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->label }}</option>
                    @endforeach
                </x-select>
            </x-form-field>

            <x-form-field label="パスワード" for="password"
                :hint="$isEdit ? '変更する場合のみ入力' : '8文字以上'" :error="$errors->get('password')">
                <x-input id="password" name="password" type="password" :error="$errors->has('password')" autocomplete="new-password" />
            </x-form-field>

            <x-form-field label="パスワード（確認）" for="password_confirmation">
                <x-input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            </x-form-field>

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">{{ $isEdit ? '更新' : '作成' }}</x-button>
                <x-link-button :href="route('users.index')" variant="ghost">キャンセル</x-link-button>
            </div>
        </form>
    </x-card>
</x-layouts.app>
