@props([
    'variant' => 'info',
    'title' => null,
])

@php
    $variants = [
        'info' => 'border-brand/30 bg-brand/5 text-ink',
        'success' => 'border-[#22C55E]/40 bg-[#22C55E]/10 text-ink',
        'warning' => 'border-amber-300 bg-amber-50 text-ink',
        'danger' => 'border-red-300 bg-red-50 text-ink',
    ];
@endphp

<div role="alert" {{ $attributes->class(['rounded-xl border px-4 py-3 text-sm', $variants[$variant] ?? $variants['info']]) }}>
    @if ($title)
        <p class="font-semibold">{{ $title }}</p>
    @endif
    <div class="{{ $title ? 'mt-1 text-slate-600' : '' }}">{{ $slot }}</div>
</div>
