@props(['items' => []])

<nav aria-label="パンくずリスト" {{ $attributes }}>
    <ol class="flex flex-wrap items-center gap-1.5 text-sm text-slate-500">
        @foreach ($items as $item)
            <li class="flex items-center gap-1.5">
                @if (! $loop->first)
                    <span class="text-slate-300" aria-hidden="true">/</span>
                @endif
                @if (! empty($item['href']) && ! $loop->last)
                    <a href="{{ $item['href'] }}" class="transition hover:text-brand">{{ $item['label'] }}</a>
                @else
                    <span class="font-medium text-ink" @if ($loop->last) aria-current="page" @endif>{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
