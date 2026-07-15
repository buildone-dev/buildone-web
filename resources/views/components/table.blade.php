@props(['head' => null])

<div {{ $attributes->class('overflow-x-auto rounded-xl border border-slate-200 bg-white') }}>
    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
        @isset($head)
            <thead class="bg-surface text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>{{ $head }}</tr>
            </thead>
        @endisset
        <tbody class="divide-y divide-slate-100 text-ink">
            {{ $slot }}
        </tbody>
    </table>
</div>
