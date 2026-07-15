@props(['lines' => 1])

<div {{ $attributes->class('space-y-2') }} aria-hidden="true">
    @for ($i = 0; $i < (int) $lines; $i++)
        <div class="h-4 animate-pulse rounded bg-slate-200 {{ $i === (int) $lines - 1 && $lines > 1 ? 'w-2/3' : 'w-full' }}"></div>
    @endfor
</div>
