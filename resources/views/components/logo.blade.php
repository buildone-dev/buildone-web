@props([
    'size' => 30,
    'showText' => true,
    'textClass' => 'text-xl',
])

{{-- BuildOne ロゴ。「開いた扉＝そのシステム作れます」を表すマーク＋ワードマーク。 --}}
<span {{ $attributes->class('inline-flex items-center gap-2') }} aria-label="BuildOne">
    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="shrink-0">
        <path d="M42 12 H18 a3 3 0 0 0 -3 3 V49 a3 3 0 0 0 3 3 H42" stroke="#2563EB" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M30 17 L42 21 V43 L30 47 Z" fill="#2563EB"/>
        <circle cx="36" cy="32" r="2" fill="#FFFFFF"/>
    </svg>
    @if ($showText)
        <span class="{{ $textClass }} font-bold tracking-tight">Build<span class="text-brand">One</span></span>
    @endif
</span>
