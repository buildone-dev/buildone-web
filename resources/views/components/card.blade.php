@props(['title' => null])

<div {{ $attributes->class('rounded-2xl border border-slate-200 bg-white') }}>
    @if ($title || isset($actions))
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
            @if ($title)
                <h3 class="font-bold text-ink">{{ $title }}</h3>
            @endif
            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
