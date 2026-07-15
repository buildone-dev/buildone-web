@php($isEdit = $reservation->exists)

<x-layouts.app :title="$isEdit ? '予約編集' : '予約登録'">
    <x-slot:header>
        <x-breadcrumb :items="[
            ['label' => 'ダッシュボード', 'href' => route('dashboard')],
            ['label' => '予約', 'href' => route('reserve.index')],
            ['label' => $isEdit ? '編集' : '登録'],
        ]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">{{ $isEdit ? '予約編集' : '予約登録' }}</h2>
    </x-slot:header>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ $isEdit ? route('reserve.update', $reservation) : route('reserve.store') }}" class="space-y-5">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <x-form-field label="顧客名" for="customer_name" :required="true" :error="$errors->get('customer_name')">
                <x-input id="customer_name" name="customer_name" value="{{ old('customer_name', $reservation->customer_name) }}" :error="$errors->has('customer_name')" required />
            </x-form-field>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-form-field label="電話番号（任意）" for="phone" :error="$errors->get('phone')">
                    <x-input id="phone" name="phone" value="{{ old('phone', $reservation->phone) }}" :error="$errors->has('phone')" />
                </x-form-field>
                <x-form-field label="メール（任意）" for="email" :error="$errors->get('email')">
                    <x-input id="email" name="email" type="email" value="{{ old('email', $reservation->email) }}" :error="$errors->has('email')" />
                </x-form-field>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-form-field label="予約日時" for="reserved_at" :required="true" :error="$errors->get('reserved_at')">
                    <x-input id="reserved_at" name="reserved_at" type="datetime-local"
                        value="{{ old('reserved_at', optional($reservation->reserved_at)->format('Y-m-d\TH:i')) }}" :error="$errors->has('reserved_at')" required />
                </x-form-field>
                <x-form-field label="人数" for="party_size" :required="true" :error="$errors->get('party_size')">
                    <x-input id="party_size" name="party_size" type="number" min="1" value="{{ old('party_size', $reservation->party_size ?? 1) }}" :error="$errors->has('party_size')" required />
                </x-form-field>
            </div>

            <x-form-field label="メモ（任意）" for="note" :error="$errors->get('note')">
                <x-input id="note" name="note" value="{{ old('note', $reservation->note) }}" :error="$errors->has('note')" placeholder="例：窓際希望" />
            </x-form-field>

            <div class="flex items-center gap-3 pt-2">
                <x-button type="submit">{{ $isEdit ? '更新' : '登録' }}</x-button>
                <x-link-button :href="route('reserve.index')" variant="ghost">キャンセル</x-link-button>
            </div>
        </form>
    </x-card>
</x-layouts.app>
