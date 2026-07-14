@extends('layouts.app')

@section('title', 'エラーが発生しました | BuildOne')
@section('meta_description', 'サーバーでエラーが発生しました。')

@section('content')
    <section class="bg-white">
        <div class="mx-auto flex max-w-3xl flex-col items-center px-4 py-24 text-center sm:px-6 sm:py-32">
            <p class="text-6xl font-bold text-brand">500</p>
            <h1 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl">エラーが発生しました</h1>
            <p class="mt-4 leading-relaxed text-slate-600">
                申し訳ありません。サーバーで問題が発生しました。<br>
                時間をおいて再度お試しください。
            </p>
            <a href="{{ route('home') }}" class="mt-8 inline-flex items-center justify-center rounded-lg bg-brand px-6 py-3 font-semibold text-white transition hover:bg-brand-dark">
                トップページへ戻る
            </a>
        </div>
    </section>
@endsection
