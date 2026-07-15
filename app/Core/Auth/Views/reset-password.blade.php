<x-layouts.guest title="新しいパスワード">
    <x-card>
        <h1 class="text-xl font-bold text-ink">新しいパスワードの設定</h1>

        <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <x-form-field label="メールアドレス" for="email" :error="$errors->get('email')">
                <x-input id="email" name="email" type="email" value="{{ old('email', $email) }}"
                    :error="$errors->has('email')" required autocomplete="username" />
            </x-form-field>

            <x-form-field label="新しいパスワード" for="password" hint="8文字以上" :error="$errors->get('password')">
                <x-input id="password" name="password" type="password" :error="$errors->has('password')" required autocomplete="new-password" />
            </x-form-field>

            <x-form-field label="新しいパスワード（確認）" for="password_confirmation">
                <x-input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" />
            </x-form-field>

            <x-button type="submit" class="w-full">パスワードを再設定</x-button>
        </form>
    </x-card>
</x-layouts.guest>
