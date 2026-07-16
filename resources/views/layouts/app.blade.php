<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BuildOne | 社長、そのシステム作れます。')</title>
    <meta name="description" content="@yield('meta_description', 'AIで会社の業務システムを1週間で開発・公開。QR・ICカード入退室管理、勤怠管理、予約管理など、企業の現場で本当に使えるシステムを企画から検証まで公開します。')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- OGP --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="BuildOne">
    <meta property="og:title" content="@yield('title', 'BuildOne | 社長、そのシステム作れます。')">
    <meta property="og:description" content="@yield('meta_description', 'AIで会社の業務システムを1週間で開発・公開。企業の現場で本当に使えるシステムを企画から検証まで公開します。')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="{{ asset('ogp.svg') }}">
    <meta property="og:locale" content="ja_JP">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

    @stack('structured_data')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-sans text-ink antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-brand focus:px-4 focus:py-2 focus:text-white">本文へスキップ</a>

    <header class="fixed inset-x-0 top-0 z-40 border-b border-slate-200/70 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('home') }}" class="text-ink" aria-label="BuildOne トップページ">
                <x-logo :size="30" />
            </a>

            <nav class="hidden items-center gap-7 text-sm font-medium text-slate-700 md:flex" aria-label="メインナビゲーション">
                <a href="{{ route('home') }}#about" class="transition hover:text-brand">BuildOneとは</a>
                <a href="{{ route('home') }}#entry" class="transition hover:text-brand">入退室システム</a>
                <a href="{{ route('home') }}#systems" class="transition hover:text-brand">開発システム</a>
                <a href="{{ route('home') }}#youtube" class="transition hover:text-brand">YouTube</a>
                <a href="{{ route('home') }}#process" class="transition hover:text-brand">開発の流れ</a>
                <a href="{{ route('home') }}#contact" class="rounded-lg bg-brand px-4 py-2 text-white transition hover:bg-brand-dark">お問い合わせ</a>
            </nav>

            <button id="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="メニューを開閉する"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-700 hover:bg-slate-100 md:hidden">
                <svg data-icon="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg data-icon="close" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav id="mobile-menu" class="hidden border-t border-slate-200 bg-white px-4 pb-4 pt-2 md:hidden" aria-label="モバイルナビゲーション">
            <ul class="flex flex-col gap-1 text-sm font-medium text-slate-700">
                <li><a href="{{ route('home') }}#about" class="block rounded-lg px-3 py-2.5 hover:bg-slate-50 hover:text-brand">BuildOneとは</a></li>
                <li><a href="{{ route('home') }}#entry" class="block rounded-lg px-3 py-2.5 hover:bg-slate-50 hover:text-brand">入退室システム</a></li>
                <li><a href="{{ route('home') }}#systems" class="block rounded-lg px-3 py-2.5 hover:bg-slate-50 hover:text-brand">開発システム</a></li>
                <li><a href="{{ route('home') }}#youtube" class="block rounded-lg px-3 py-2.5 hover:bg-slate-50 hover:text-brand">YouTube</a></li>
                <li><a href="{{ route('home') }}#process" class="block rounded-lg px-3 py-2.5 hover:bg-slate-50 hover:text-brand">開発の流れ</a></li>
                <li class="pt-1"><a href="{{ route('home') }}#contact" class="block rounded-lg bg-brand px-3 py-2.5 text-center text-white hover:bg-brand-dark">お問い合わせ</a></li>
            </ul>
        </nav>
    </header>

    <main id="main" class="pt-16">
        @yield('content')
    </main>

    <footer class="bg-ink text-slate-300">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
            <div class="flex flex-col gap-8 sm:flex-row sm:items-start sm:justify-between">
                <div class="text-white">
                    <x-logo :size="30" text-class="text-xl" />
                    <p class="mt-2 text-sm text-slate-300">社長、そのシステム作れます。</p>
                    <p class="mt-1 text-sm text-slate-400">YouTube {{ config('buildone.youtube.handle') }}</p>
                </div>
                <nav aria-label="フッターナビゲーション">
                    <ul class="flex flex-col gap-2 text-sm sm:items-end">
                        @if (setting('youtube_url', config('buildone.youtube.url')))
                            <li><a href="{{ setting('youtube_url', config('buildone.youtube.url')) }}" rel="noopener" target="_blank" class="transition hover:text-white">YouTube</a></li>
                        @endif
                        <li><a href="{{ route('privacy') }}" class="transition hover:text-white">プライバシーポリシー</a></li>
                        <li><a href="{{ route('terms') }}" class="transition hover:text-white">利用規約</a></li>
                        <li><a href="{{ route('home') }}#contact" class="transition hover:text-white">お問い合わせ</a></li>
                    </ul>
                </nav>
            </div>
            <p class="mt-10 border-t border-slate-700/60 pt-6 text-xs text-slate-400">
                運営：{{ config('buildone.company.name') }}｜〒{{ config('buildone.company.postal_code') }} {{ config('buildone.company.address') }}
            </p>
            <p class="mt-1 text-xs text-slate-400">
                &copy; {{ date('Y') }} BuildOne. All rights reserved.
            </p>
        </div>
    </footer>

    <x-inquiry-modal />
    <x-chat-widget />
</body>
</html>
