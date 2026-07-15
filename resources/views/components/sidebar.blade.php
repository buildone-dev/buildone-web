{{--
    サイドバー。M2では暫定的にメニューを定義。M3で Core\Menu レジストリに置き換える。
    メニュー項目: [label, href, icon(svg path), active(bool)]
--}}
@php
    $menu = [
        ['label' => 'ダッシュボード', 'href' => '#', 'active' => true, 'icon' => 'M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75'],
        ['label' => 'ユーザー', 'href' => '#', 'active' => false, 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
        ['label' => '権限', 'href' => '#', 'active' => false, 'icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
        ['label' => '監査ログ', 'href' => '#', 'active' => false, 'icon' => 'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z'],
        ['label' => '設定', 'href' => '#', 'active' => false, 'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z'],
    ];
@endphp

<div id="app-sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-ink/40 lg:hidden"></div>

<aside id="app-sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full border-r border-slate-200 bg-white transition-transform lg:static lg:translate-x-0"
    aria-label="サイドバー">
    <div class="flex h-16 items-center gap-2 border-b border-slate-200 px-6">
        <a href="/" class="text-xl font-bold tracking-tight">Build<span class="text-brand">One</span></a>
    </div>
    <nav class="p-3" aria-label="メインメニュー">
        <ul class="space-y-1">
            @foreach ($menu as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                        @if ($item['active']) aria-current="page" @endif
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ $item['active'] ? 'bg-brand/10 text-brand' : 'text-slate-700 hover:bg-slate-50 hover:text-ink' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>
