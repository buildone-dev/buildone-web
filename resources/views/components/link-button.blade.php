@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => '#',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg font-semibold transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2';
    $variants = [
        'primary' => 'bg-brand text-white hover:bg-brand-dark focus-visible:outline-brand',
        'secondary' => 'border border-slate-300 bg-white text-ink hover:border-brand hover:text-brand focus-visible:outline-brand',
        'ghost' => 'text-ink hover:bg-slate-100 focus-visible:outline-brand',
    ];
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
@endphp

<a href="{{ $href }}"
    {{ $attributes->class([$base, $variants[$variant] ?? $variants['primary'], $sizes[$size] ?? $sizes['md']]) }}>
    {{ $slot }}
</a>
