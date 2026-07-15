@props(['label' => null])

<label class="inline-flex items-center gap-2.5 text-sm text-ink">
    <input type="checkbox"
        {{ $attributes->class('h-4 w-4 rounded border-slate-300 text-brand focus:ring-2 focus:ring-brand/30') }} />
    @if ($label)
        <span>{{ $label }}</span>
    @endif
    {{ $slot }}
</label>
