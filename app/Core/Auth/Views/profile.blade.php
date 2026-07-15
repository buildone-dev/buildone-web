@php($user = auth()->user())

<x-layouts.app title="プロフィール">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => 'プロフィール']]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">プロフィール</h2>
    </x-slot:header>

    @if (session('status'))
        <x-alert variant="success" class="mb-6">{{ session('status') }}</x-alert>
    @endif

    <div class="grid max-w-2xl gap-6">
        {{-- 基本情報 --}}
        <x-card title="基本情報">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf @method('PUT')

                <x-form-field label="名前" for="name" :required="true" :error="$errors->updateProfile->get('name')">
                    <x-input id="name" name="name" value="{{ old('name', $user->name) }}"
                        :error="$errors->updateProfile->has('name')" required />
                </x-form-field>

                <x-form-field label="メールアドレス" for="email" :required="true" :error="$errors->updateProfile->get('email')">
                    <x-input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                        :error="$errors->updateProfile->has('email')" required />
                </x-form-field>

                <x-button type="submit">プロフィールを更新</x-button>
            </form>
        </x-card>

        {{-- パスワード変更 --}}
        <x-card title="パスワード変更">
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
                @csrf @method('PUT')

                <x-form-field label="現在のパスワード" for="current_password"
                    :required="true" :error="$errors->updatePassword->get('current_password')">
                    <x-input id="current_password" name="current_password" type="password"
                        :error="$errors->updatePassword->has('current_password')" required autocomplete="current-password" />
                </x-form-field>

                <x-form-field label="新しいパスワード" for="password" :required="true"
                    hint="8文字以上" :error="$errors->updatePassword->get('password')">
                    <x-input id="password" name="password" type="password"
                        :error="$errors->updatePassword->has('password')" required autocomplete="new-password" />
                </x-form-field>

                <x-form-field label="新しいパスワード（確認）" for="password_confirmation" :required="true">
                    <x-input id="password_confirmation" name="password_confirmation" type="password"
                        required autocomplete="new-password" />
                </x-form-field>

                <x-button type="submit">パスワードを変更</x-button>
            </form>
        </x-card>
    </div>
</x-layouts.app>
