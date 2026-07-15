@props(['messages' => []])

@if (! empty($messages))
    <p class="mt-1.5 text-sm text-red-600">
        {{ is_array($messages) ? implode(' / ', $messages) : $messages }}
    </p>
@endif
