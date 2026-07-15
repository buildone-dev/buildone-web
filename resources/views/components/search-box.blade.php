@props(['placeholder' => '検索…', 'value' => null])

{{-- GET検索フォーム。現在のクエリ(q)を保持して送信する。 --}}
<form method="GET" {{ $attributes->class('relative w-full sm:w-64') }} role="search">
    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400" aria-hidden="true">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
    </span>
    <input type="search" name="q" value="{{ $value ?? request('q') }}" placeholder="{{ $placeholder }}"
        aria-label="{{ $placeholder }}"
        class="block w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-sm text-ink shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30" />
</form>
