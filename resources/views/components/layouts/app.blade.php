@props(['title' => 'ダッシュボード'])

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | BuildOne</title>
    <meta name="robots" content="noindex">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface font-sans text-ink antialiased">
    <a href="#app-main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-brand focus:px-4 focus:py-2 focus:text-white">本文へスキップ</a>

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex min-w-0 flex-1 flex-col">
            <x-header :title="$title" />

            <main id="app-main" class="flex-1 p-4 sm:p-6 lg:p-8">
                @isset($header)
                    <div class="mb-6">{{ $header }}</div>
                @endisset
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-toast />
</body>
</html>
