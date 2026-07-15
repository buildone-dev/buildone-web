<x-layouts.guest title="ログイン">
    <x-card>
        <h1 class="text-xl font-bold text-ink">管理画面にログイン</h1>
        <p class="mt-1 text-sm text-slate-500">BuildOne Framework</p>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
            @csrf

            <x-form-field label="メールアドレス" for="email" :error="$errors->get('email')">
                <x-input id="email" name="email" type="email" value="{{ old('email') }}"
                    :error="$errors->has('email')" required autofocus autocomplete="username" />
            </x-form-field>

            <x-form-field label="パスワード" for="password">
                <x-input id="password" name="password" type="password"
                    required autocomplete="current-password" />
            </x-form-field>

            <x-checkbox name="remember" label="ログイン状態を保持する" />

            <x-button type="submit" class="w-full">ログイン</x-button>
        </form>
    </x-card>
</x-layouts.guest>
