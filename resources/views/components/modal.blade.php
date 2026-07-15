@props([
    'id',
    'title' => null,
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
    ];
@endphp

<div id="{{ $id }}" data-modal class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div data-modal-backdrop class="absolute inset-0 bg-ink/50"></div>
    <div class="relative flex min-h-full items-center justify-center p-4">
        <div role="dialog" aria-modal="true" @if ($title) aria-label="{{ $title }}" @endif
            class="flex max-h-[90vh] w-full {{ $sizes[$size] ?? $sizes['md'] }} flex-col rounded-2xl bg-white shadow-xl">
            <div class="flex shrink-0 items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
                <h3 class="font-bold text-ink">{{ $title }}</h3>
                <button type="button" data-modal-close aria-label="閉じる"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-ink">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="min-h-0 overflow-y-auto p-6">{{ $slot }}</div>
            @isset($footer)
                <div class="flex shrink-0 justify-end gap-2 border-t border-slate-100 px-6 py-4">{{ $footer }}</div>
            @endisset
        </div>
    </div>
</div>
