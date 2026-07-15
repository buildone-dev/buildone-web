@props([
    'label' => null,
    'for' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
])

<div {{ $attributes->only('class') }}>
    @if ($label)
        <label @if ($for) for="{{ $for }}" @endif class="mb-1.5 block text-sm font-medium text-ink">
            {{ $label }}
            @if ($required)
                <span class="text-red-500" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($hint && ! $error)
        <p class="mt-1.5 text-xs text-slate-500">{{ $hint }}</p>
    @endif

    <x-validation-error :messages="$error" />
</div>
