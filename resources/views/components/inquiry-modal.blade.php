{{-- 問い合わせモーダル。CTAボタン（data-modal-open="inquiry-modal"）から開く。 --}}
<x-modal id="inquiry-modal" title="お問い合わせ" size="lg">
    <p class="mb-4 text-sm text-slate-600">御社の業務に合わせた解決方法をご提案します。まずは現在のお悩みをお聞かせください。</p>
    <x-inquiry-form />
</x-modal>
