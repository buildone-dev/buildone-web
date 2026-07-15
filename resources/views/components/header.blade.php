@props(['title' => 'ダッシュボード'])

<header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6">
    <button type="button" data-sidebar-toggle
        class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-700 hover:bg-slate-100 lg:hidden"
        aria-label="メニューを開閉">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <h1 class="truncate text-lg font-semibold text-ink">{{ $title }}</h1>

    <div class="ml-auto flex items-center gap-2">
        <div class="relative">
            <button type="button" data-dropdown-toggle="header-user-menu" aria-expanded="false" aria-haspopup="true"
                class="inline-flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-100">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand/10 text-xs font-bold text-brand">{{ mb_substr(auth()->user()->name ?? 'BO', 0, 1) }}</span>
                <span class="hidden sm:inline">{{ auth()->user()->name ?? 'ゲスト' }}</span>
            </button>
            <div id="header-user-menu" data-dropdown-panel
                class="absolute right-0 mt-2 hidden w-44 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                @auth
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">プロフィール</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">ログアウト</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</header>
