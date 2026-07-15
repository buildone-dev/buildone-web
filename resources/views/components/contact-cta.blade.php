@props([
    'title' => null,
    'message' => null,
    'buttonText' => null,
])

{{-- 問い合わせ誘導カード（固定文）。通常のAI回答と区別できるカード形式。 --}}
<div {{ $attributes->class('rounded-2xl border border-brand/30 bg-brand/5 p-5') }}>
    <p class="font-bold text-ink">{{ $title ?? config('buildone.contact_cta.title') }}</p>
    <p class="mt-1.5 text-sm leading-relaxed text-slate-600">{{ $message ?? config('buildone.contact_cta.message') }}</p>
    <div class="mt-4">
        <x-button type="button" data-modal-open="inquiry-modal">
            {{ $buttonText ?? config('buildone.contact_cta.button_text') }}
        </x-button>
    </div>
</div>
