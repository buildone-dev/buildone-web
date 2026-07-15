<x-layouts.guest title="パスワード再設定">
    <x-card>
        <h1 class="text-xl font-bold text-ink">パスワードの再設定</h1>
        <p class="mt-1 text-sm text-slate-500">登録メールアドレスに再設定用リンクをお送りします。</p>

        @if (session('status'))
            <x-alert variant="success" class="mt-4">{{ session('status') }}</x-alert>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
            @csrf
            <x-form-field label="メールアドレス" for="email" :error="$errors->get('email')">
                <x-input id="email" name="email" type="email" value="{{ old('email') }}"
                    :error="$errors->has('email')" required autofocus autocomplete="username" />
            </x-form-field>
            <x-button type="submit" class="w-full">再設定リンクを送信</x-button>
        </form>

        <p class="mt-5 text-center text-sm">
            <a href="{{ route('login') }}" class="text-brand underline underline-offset-2 hover:text-brand-dark">ログインへ戻る</a>
        </p>
    </x-card>
</x-layouts.guest>
