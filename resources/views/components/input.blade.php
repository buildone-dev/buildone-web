@props([
    'type' => 'text',
    'error' => false,
])

<input type="{{ $type }}"
    {{ $attributes->class([
        'block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-ink shadow-sm transition placeholder:text-slate-400 focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-slate-50',
        'border-slate-300 focus:border-brand focus:ring-brand/30' => ! $error,
        'border-red-400 focus:border-red-500 focus:ring-red-200' => $error,
    ]) }}
    @if ($error) aria-invalid="true" @endif />
