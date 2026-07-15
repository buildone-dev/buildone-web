<x-layouts.app title="打刻">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '入退室'], ['label' => '打刻']]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">打刻</h2>
        <p class="mt-1 text-sm text-slate-500">QR・ICカードをかざすか、コードを入力してください。</p>
    </x-slot:header>

    @if (session('punch_result'))
        <div class="mb-6 rounded-2xl border px-6 py-5 text-center {{ session('punch_direction') === 'in' ? 'border-[#22C55E]/40 bg-[#22C55E]/10' : 'border-brand/40 bg-brand/5' }}">
            <p class="text-2xl font-bold {{ session('punch_direction') === 'in' ? 'text-[#15803D]' : 'text-brand' }}">
                {{ session('punch_result') }}
            </p>
        </div>
    @elseif (session('punch_error'))
        <x-alert variant="danger" class="mb-6">{{ session('punch_error') }}</x-alert>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <x-card class="lg:col-span-2">
            <form method="POST" action="{{ route('entry.punch') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                @csrf
                <div class="flex-1">
                    <label for="code" class="mb-1.5 block text-sm font-medium text-ink">コード（QR / ICカード）</label>
                    <input id="code" name="code" type="text" autofocus autocomplete="off"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-4 text-lg text-ink shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30"
                        placeholder="かざす、または入力してEnter" />
                </div>
                <x-button type="submit" size="lg">打刻する</x-button>
            </form>
            @error('code')
                <x-validation-error :messages="$message" />
            @enderror
        </x-card>

        <x-stat-card label="現在の在室" :value="$presentCount" />
    </div>

    <x-card title="最近の打刻" class="mt-6">
        @forelse ($recent as $log)
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 py-2.5 last:border-0">
                <div class="flex items-center gap-3">
                    <x-badge :variant="$log->isEntry() ? 'success' : 'brand'">{{ $log->isEntry() ? '入室' : '退室' }}</x-badge>
                    <span class="text-sm font-medium text-ink">{{ $log->member->name }}</span>
                </div>
                <time class="text-xs text-slate-400">{{ $log->recorded_at->format('H:i') }}</time>
            </div>
        @empty
            <x-empty-state title="まだ打刻がありません" />
        @endforelse
    </x-card>
</x-layouts.app>
