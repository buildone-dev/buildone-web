@props([
    'title' => 'データがありません',
    'description' => null,
])

<div {{ $attributes->class('flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center') }}>
    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400" aria-hidden="true">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
        </svg>
    </span>
    <p class="mt-4 font-semibold text-ink">{{ $title }}</p>
    @if ($description)
        <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-6">{{ $action }}</div>
    @endisset
</div>
