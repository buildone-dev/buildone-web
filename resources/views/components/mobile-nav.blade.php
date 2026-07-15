@props(['items' => []])

{{-- モバイル用ボトムナビゲーション。items: [label, href, icon(svg path), active] --}}
<nav class="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-white/95 backdrop-blur lg:hidden"
    aria-label="モバイルナビゲーション">
    <ul class="flex items-stretch justify-around">
        @foreach ($items as $item)
            <li class="flex-1">
                <a href="{{ $item['href'] ?? '#' }}"
                    @if (! empty($item['active'])) aria-current="page" @endif
                    class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-medium transition
                        {{ ! empty($item['active']) ? 'text-brand' : 'text-slate-500 hover:text-ink' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] ?? '' }}" />
                    </svg>
                    {{ $item['label'] ?? '' }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
