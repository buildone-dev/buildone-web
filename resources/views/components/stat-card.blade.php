@props([
    'label' => '',
    'value' => '',
    'delta' => null,
    'trend' => 'up',
])

<div {{ $attributes->class('rounded-2xl border border-slate-200 bg-white p-5') }}>
    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
    <p class="mt-2 text-2xl font-bold text-ink">{{ $value }}</p>
    @if ($delta !== null)
        <p class="mt-1 text-xs font-semibold {{ $trend === 'down' ? 'text-red-500' : 'text-[#16A34A]' }}">
            {{ $trend === 'down' ? '▼' : '▲' }} {{ $delta }}
        </p>
    @endif
</div>
