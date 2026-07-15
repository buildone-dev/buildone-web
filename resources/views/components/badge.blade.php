@props(['variant' => 'neutral'])

@php
    $variants = [
        'neutral' => 'bg-slate-100 text-slate-600',
        'brand' => 'bg-brand/10 text-brand',
        'success' => 'bg-[#22C55E]/15 text-[#15803D]',
        'warning' => 'bg-amber-100 text-amber-700',
        'danger' => 'bg-red-100 text-red-700',
    ];
@endphp

<span {{ $attributes->class(['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold', $variants[$variant] ?? $variants['neutral']]) }}>
    {{ $slot }}
</span>
