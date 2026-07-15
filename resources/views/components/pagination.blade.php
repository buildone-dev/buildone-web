@props(['paginator' => null])

{{-- Laravel の Paginator をブランドスタイルで包む薄いラッパー。 --}}
@if ($paginator && $paginator->hasPages())
    <div {{ $attributes->class('mt-4') }}>
        {{ $paginator->onEachSide(1)->links() }}
    </div>
@endif
