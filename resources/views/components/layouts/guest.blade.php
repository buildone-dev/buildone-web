@props(['title' => 'BuildOne'])

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
    <div class="flex min-h-screen flex-col items-center justify-center p-4">
        <a href="/" class="mb-6 text-ink" aria-label="BuildOne">
            <x-logo :size="36" text-class="text-2xl" />
        </a>
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
