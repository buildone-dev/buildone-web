@extends('layouts.app')

@section('title', 'BuildOne | 社長、そのシステム作れます。')
@section('meta_description', 'AIで会社の業務システムを1週間で開発・公開。QR・ICカード入退室管理、勤怠管理、予約管理、シフト管理など、企業の現場で本当に使えるシステムを企画から検証まで公開します。')
@section('canonical', route('home'))

@push('structured_data')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "BuildOne",
        "alternateName": "BuildOne | 社長、そのシステム作れます。",
        "url": "{{ route('home') }}",
        "description": "AIで会社の業務システムを1週間で開発・公開するプロジェクト",
        "inLanguage": "ja"
    }
    </script>
@endpush

@section('content')

    {{-- ============ ファーストビュー ============ --}}
    <section class="relative overflow-hidden bg-white">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -right-40 -top-40 h-96 w-96 rounded-full bg-brand/5"></div>
            <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-accent/5"></div>
        </div>

        <div class="relative mx-auto grid max-w-6xl gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-2 lg:items-center lg:gap-8 lg:py-28">
            <div>
                <p class="inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brand/5 px-3 py-1 text-xs font-semibold text-brand">
                    <span class="h-1.5 w-1.5 rounded-full bg-accent" aria-hidden="true"></span>
                    AI × 業務システム開発プロジェクト
                </p>
                <h1 class="mt-5 text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
                    社長、<br class="sm:hidden">そのシステム<br class="hidden sm:block">作れます。
                </h1>
                <p class="mt-5 text-lg font-medium text-brand sm:text-xl">
                    {{ config('buildone.subcopy') }}
                </p>
                <p class="mt-4 max-w-xl leading-relaxed text-slate-600">
                    QR・ICカード入退室管理、勤怠管理、予約管理、シフト管理など、企業の現場で本当に使えるシステムを企画から検証まで公開します。
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="#systems" class="inline-flex items-center justify-center rounded-lg bg-brand px-6 py-3 font-semibold text-white shadow-sm transition hover:bg-brand-dark">
                        開発中のシステムを見る
                    </a>
                    @if (config('buildone.youtube.url'))
                        <a href="{{ config('buildone.youtube.url') }}" rel="noopener" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-6 py-3 font-semibold text-ink transition hover:border-brand hover:text-brand">
                            YouTubeを見る
                        </a>
                    @else
                        <a href="#youtube" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-6 py-3 font-semibold text-ink transition hover:border-brand hover:text-brand">
                            YouTubeを見る
                        </a>
                    @endif
                </div>
            </div>

            {{-- 業務システム管理画面のUIモック（画像を使わずHTML+CSSで表現） --}}
            <div aria-hidden="true" class="select-none">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-200/60">
                    <div class="flex items-center gap-2 border-b border-slate-100 px-4 py-3">
                        <span class="h-2.5 w-2.5 rounded-full bg-slate-200"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-slate-200"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-slate-200"></span>
                        <span class="ml-3 rounded bg-surface px-2 py-0.5 text-[10px] font-medium text-slate-400">BuildOne Entry — 入退室管理</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3 p-4">
                        <div class="rounded-lg bg-surface p-3">
                            <p class="text-[10px] font-medium text-slate-500">本日の入室</p>
                            <p class="mt-1 text-xl font-bold text-ink">128<span class="ml-0.5 text-[10px] font-medium text-slate-400">人</span></p>
                            <p class="mt-0.5 text-[10px] font-semibold text-accent">+12%</p>
                        </div>
                        <div class="rounded-lg bg-surface p-3">
                            <p class="text-[10px] font-medium text-slate-500">在室中</p>
                            <p class="mt-1 text-xl font-bold text-ink">42<span class="ml-0.5 text-[10px] font-medium text-slate-400">人</span></p>
                            <p class="mt-0.5 text-[10px] font-semibold text-brand">リアルタイム</p>
                        </div>
                        <div class="rounded-lg bg-surface p-3">
                            <p class="text-[10px] font-medium text-slate-500">未退室アラート</p>
                            <p class="mt-1 text-xl font-bold text-ink">0<span class="ml-0.5 text-[10px] font-medium text-slate-400">件</span></p>
                            <p class="mt-0.5 text-[10px] font-semibold text-accent">正常</p>
                        </div>
                        <div class="col-span-3 rounded-lg border border-slate-100 p-3">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-semibold text-slate-500">最近の入退室</p>
                                <span class="rounded bg-brand/10 px-1.5 py-0.5 text-[9px] font-semibold text-brand">QR / ICカード</span>
                            </div>
                            <ul class="mt-2 space-y-1.5">
                                <li class="flex items-center justify-between rounded bg-surface px-2.5 py-1.5">
                                    <span class="text-[10px] font-medium text-ink">田中 さくら</span>
                                    <span class="text-[9px] text-slate-400">09:02 入室</span>
                                    <span class="rounded bg-accent/10 px-1.5 py-0.5 text-[9px] font-semibold text-accent">OK</span>
                                </li>
                                <li class="flex items-center justify-between rounded bg-surface px-2.5 py-1.5">
                                    <span class="text-[10px] font-medium text-ink">佐藤 健太</span>
                                    <span class="text-[9px] text-slate-400">09:05 入室</span>
                                    <span class="rounded bg-accent/10 px-1.5 py-0.5 text-[9px] font-semibold text-accent">OK</span>
                                </li>
                                <li class="flex items-center justify-between rounded bg-surface px-2.5 py-1.5">
                                    <span class="text-[10px] font-medium text-ink">鈴木 実</span>
                                    <span class="text-[9px] text-slate-400">08:58 退室</span>
                                    <span class="rounded bg-brand/10 px-1.5 py-0.5 text-[9px] font-semibold text-brand">記録済</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400">開発予定システムの画面イメージ</p>
            </div>
        </div>
    </section>

    {{-- ============ BuildOneとは ============ --}}
    <section id="about" class="scroll-mt-20 bg-surface">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24">
            <p class="text-sm font-semibold uppercase tracking-widest text-brand">About</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">BuildOneとは</h2>
            <div class="mt-6 max-w-3xl space-y-4 leading-relaxed text-slate-600">
                <p>
                    BuildOneは、AIを活用し、企業で本当に使える業務システムを<strong class="font-semibold text-ink">1週間単位で開発・公開</strong>するプロジェクトです。
                </p>
                <p>
                    完成形だけをお見せするのではありません。<strong class="font-semibold text-ink">設計、開発、失敗、修正、検証まで</strong>——そのすべての過程をYouTubeで公開します。
                </p>
            </div>
        </div>
    </section>

    {{-- ============ 開発システム一覧 ============ --}}
    <section id="systems" class="scroll-mt-20 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24">
            <p class="text-sm font-semibold uppercase tracking-widest text-brand">Systems</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">開発システム</h2>
            <p class="mt-4 max-w-2xl text-slate-600">現場の「困った」から生まれる業務システムを、順番に開発していきます。</p>

            <ul class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (config('buildone.systems') as $system)
                    <li class="flex flex-col rounded-2xl border p-6 transition {{ $system['featured'] ? 'border-brand/40 bg-brand/[0.03] shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-lg font-bold text-ink">{{ $system['name'] }}</h3>
                            <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $system['featured'] ? 'bg-accent/15 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $system['status'] }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $system['description'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ============ BuildOneの特徴 ============ --}}
    <section id="features" class="scroll-mt-20 bg-surface">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24">
            <p class="text-sm font-semibold uppercase tracking-widest text-brand">Features</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">BuildOneの特徴</h2>

            <ul class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (config('buildone.features') as $feature)
                    <li class="rounded-2xl border border-slate-200 bg-white p-6">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-brand/10 text-brand" aria-hidden="true">
                            @switch($feature['icon'])
                                @case('sparkles')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" /></svg>
                                    @break
                                @case('bolt')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" /></svg>
                                    @break
                                @case('cog')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m0 17.726-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205 12 12m6.894 5.785-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" /></svg>
                                    @break
                                @case('camera')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                                    @break
                                @case('check')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    @break
                                @default
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" /></svg>
                            @endswitch
                        </span>
                        <h3 class="mt-4 font-bold text-ink">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $feature['text'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ============ 開発の流れ ============ --}}
    <section id="process" class="scroll-mt-20 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24">
            <p class="text-sm font-semibold uppercase tracking-widest text-brand">Process</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">開発の流れ</h2>

            <ol class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (config('buildone.process') as $step)
                    <li class="relative rounded-2xl border border-slate-200 bg-white p-6">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-ink text-sm font-bold text-white" aria-hidden="true">{{ $step['step'] }}</span>
                        <h3 class="mt-4 font-bold text-ink">
                            <span class="sr-only">ステップ{{ $step['step'] }}：</span>{{ $step['title'] }}
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ============ YouTube ============ --}}
    <section id="youtube" class="scroll-mt-20 bg-ink text-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-accent">YouTube</p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">{{ config('buildone.youtube.channel_name') }}</h2>
                    <p class="mt-3 font-medium text-slate-300">{{ config('buildone.youtube.handle') }}</p>
                    <p class="mt-5 max-w-xl leading-relaxed text-slate-300">
                        業務システムを1週間で作る過程を、成功も失敗も含めて公開します。
                    </p>
                    @if (config('buildone.youtube.url'))
                        <a href="{{ config('buildone.youtube.url') }}" rel="noopener" target="_blank" class="mt-7 inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 font-semibold text-ink transition hover:bg-slate-100">
                            チャンネルを見る
                        </a>
                    @else
                        <p class="mt-7 inline-flex items-center gap-2 rounded-lg border border-slate-600 px-6 py-3 text-sm font-semibold text-slate-300">
                            チャンネル準備中 — {{ config('buildone.youtube.handle') }}
                        </p>
                    @endif
                </div>
                <div>
                    @if (config('buildone.youtube.video_url'))
                        <div class="aspect-video overflow-hidden rounded-2xl border border-slate-700">
                            <iframe class="h-full w-full" src="{{ config('buildone.youtube.video_url') }}" title="BuildOne 最新動画" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="flex aspect-video flex-col items-center justify-center rounded-2xl border border-dashed border-slate-600 bg-slate-800/40">
                            <svg class="h-12 w-12 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <p class="mt-3 text-sm font-semibold text-slate-400">動画は近日公開</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ============ お問い合わせ ============ --}}
    <section id="contact" class="scroll-mt-20 bg-surface">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-brand">Contact</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                    御社の「これ、システムにできない？」を<br class="hidden sm:block">聞かせてください。
                </h2>
            </div>

            <ul class="mx-auto mt-10 grid max-w-3xl gap-3 text-sm text-slate-700 sm:grid-cols-2">
                @foreach ([
                    'Excelや紙での管理が限界',
                    '同じ入力を何度も行っている',
                    '市販サービスが業務に合わない',
                    '小さく試してから導入したい',
                    'YouTubeで作ってほしいテーマがある',
                ] as $case)
                    <li class="flex items-start gap-2.5 rounded-xl border border-slate-200 bg-white px-4 py-3">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        {{ $case }}
                    </li>
                @endforeach
            </ul>

            <div class="mt-10 text-center">
                @if (config('buildone.contact.url'))
                    <a href="{{ config('buildone.contact.url') }}" rel="noopener" target="_blank" class="inline-flex items-center justify-center rounded-lg bg-brand px-8 py-3.5 font-semibold text-white shadow-sm transition hover:bg-brand-dark">
                        お問い合わせフォームへ
                    </a>
                @elseif (config('buildone.contact.email'))
                    <a href="mailto:{{ config('buildone.contact.email') }}" class="inline-flex items-center justify-center rounded-lg bg-brand px-8 py-3.5 font-semibold text-white shadow-sm transition hover:bg-brand-dark">
                        メールで相談する
                    </a>
                @else
                    <p class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-8 py-3.5 text-sm font-semibold text-slate-500">
                        お問い合わせ窓口準備中
                    </p>
                @endif
            </div>
        </div>
    </section>

@endsection
