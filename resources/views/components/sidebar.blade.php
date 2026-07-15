@inject('menu', 'App\Core\Menu\MenuRegistry')

{{-- サイドバー。メニューは Core\Menu\MenuRegistry から権限フィルタ済みで取得する。 --}}
<div id="app-sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-ink/40 lg:hidden"></div>

<aside id="app-sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full border-r border-slate-200 bg-white transition-transform lg:static lg:translate-x-0"
    aria-label="サイドバー">
    <div class="flex h-16 items-center gap-2 border-b border-slate-200 px-6">
        <a href="/" class="text-xl font-bold tracking-tight">Build<span class="text-brand">One</span></a>
    </div>
    <nav class="p-3" aria-label="メインメニュー">
        <ul class="space-y-1">
            @foreach ($menu->visible() as $item)
                <li>
                    <a href="{{ $item->url() }}"
                        @if ($item->isActive()) aria-current="page" @endif
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ $item->isActive() ? 'bg-brand/10 text-brand' : 'text-slate-700 hover:bg-slate-50 hover:text-ink' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item->icon }}" />
                        </svg>
                        {{ $item->label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>
