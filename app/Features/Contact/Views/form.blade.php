@extends('layouts.app')

@section('title', 'お問い合わせ | BuildOne')
@section('meta_description', 'BuildOneへのお問い合わせ。御社の業務に合わせた業務システムのご相談を受け付けています。')

@section('content')
    <section class="bg-surface">
        <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 sm:py-24">
            @if (session('inquiry_sent'))
                <div class="rounded-2xl border border-[#22C55E]/40 bg-white p-8 text-center">
                    <span class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#22C55E]/15 text-[#15803D]" aria-hidden="true">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    </span>
                    <h1 class="mt-4 text-2xl font-bold tracking-tight text-ink">お問い合わせありがとうございます。</h1>
                    <p class="mt-3 leading-relaxed text-slate-600">
                        内容を確認のうえ、担当者よりご連絡いたします。<br>
                        現在の業務内容や使用中のExcel、紙の帳票、既存システムなどが分かる資料がある場合は、ご相談時にご用意いただくと、より具体的なご提案が可能です。
                    </p>
                    <div class="mt-8">
                        <x-link-button :href="route('home')" variant="secondary">トップページへ戻る</x-link-button>
                    </div>
                </div>
            @else
                <div class="mb-8">
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">お問い合わせ</h1>
                    <p class="mt-3 leading-relaxed text-slate-600">
                        BuildOneでは、決まったシステムをそのまま提供するのではなく、お客様ごとに異なる業務や課題を整理し、一社一社に合った仕組みをご提案しています。「こんなこともシステム化できる？」という段階でも問題ありません。
                    </p>
                </div>
                <x-card>
                    <x-inquiry-form />
                </x-card>
            @endif
        </div>
    </section>
@endsection
