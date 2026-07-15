@props([
    'id',
    'align' => 'right',
])

<div class="relative inline-block text-left">
    <button type="button" data-dropdown-toggle="{{ $id }}" aria-expanded="false" aria-haspopup="true"
        {{ $attributes->class('inline-flex items-center gap-1.5') }}>
        {{ $trigger }}
    </button>
    <div id="{{ $id }}" data-dropdown-panel
        class="absolute {{ $align === 'left' ? 'left-0' : 'right-0' }} z-30 mt-2 hidden min-w-44 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
        {{ $slot }}
    </div>
</div>
