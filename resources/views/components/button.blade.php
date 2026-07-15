@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'loading' => false,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg font-semibold transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-60';
    $variants = [
        'primary' => 'bg-brand text-white hover:bg-brand-dark focus-visible:outline-brand',
        'secondary' => 'border border-slate-300 bg-white text-ink hover:border-brand hover:text-brand focus-visible:outline-brand',
        'ghost' => 'text-ink hover:bg-slate-100 focus-visible:outline-brand',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600',
    ];
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
@endphp

<button type="{{ $type }}"
    {{ $attributes->class([$base, $variants[$variant] ?? $variants['primary'], $sizes[$size] ?? $sizes['md']]) }}
    @disabled($loading)>
    @if ($loading)
        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
    @endif
    {{ $slot }}
</button>
