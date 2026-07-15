@extends('layouts.app')

@section('title', '利用規約 | BuildOne')
@section('meta_description', 'BuildOneの利用規約です。')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 sm:py-24">
            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">利用規約</h1>
            <p class="mt-4 text-sm text-slate-500">最終更新日: 2026年7月16日</p>
            <p class="mt-2 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-700">※ 本規約はたたき台です。正式運用前に内容のご確認・加筆をお願いします。</p>

            <div class="mt-10 space-y-10 leading-relaxed text-slate-700">
                <section>
                    <h2 class="text-xl font-bold text-ink">第1条（適用）</h2>
                    <p class="mt-3">本規約は、BuildOne（以下「当プロジェクト」）が提供するWebサイトおよびAIアシスタント・お問い合わせ機能等（以下「本サービス」）の利用条件を定めるものです。利用者は本規約に同意のうえ本サービスを利用するものとします。</p>
                </section>
                <section>
                    <h2 class="text-xl font-bold text-ink">第2条（利用）</h2>
                    <p class="mt-3">利用者は、法令および本規約を遵守し、自己の責任において本サービスを利用します。当プロジェクトは、本サービスの内容を予告なく変更・中断・終了できるものとします。</p>
                </section>
                <section>
                    <h2 class="text-xl font-bold text-ink">第3条（禁止事項）</h2>
                    <p class="mt-3">利用者は、次の行為をしてはなりません。</p>
                    <ul class="mt-2 list-disc space-y-1 pl-6">
                        <li>法令または公序良俗に違反する行為</li>
                        <li>当プロジェクトまたは第三者の権利を侵害する行為</li>
                        <li>本サービスの運営を妨害する行為、不正アクセス</li>
                        <li>虚偽の情報を登録・送信する行為</li>
                    </ul>
                </section>
                <section>
                    <h2 class="text-xl font-bold text-ink">第4条（AIアシスタント・お問い合わせ）</h2>
                    <p class="mt-3">AIアシスタントの回答は一般的な情報提供であり、正確性・完全性を保証するものではありません。個別のご提案は別途お問い合わせにて承ります。お問い合わせ・チャットで入力された情報の取り扱いは<a href="{{ route('privacy') }}" class="font-medium text-brand underline underline-offset-2 hover:text-brand-dark">プライバシーポリシー</a>に従います。個人情報・機密情報の入力はお控えください。</p>
                </section>
                <section>
                    <h2 class="text-xl font-bold text-ink">第5条（免責事項）</h2>
                    <p class="mt-3">当プロジェクトは、本サービスの利用により利用者に生じた損害について、当プロジェクトの故意または重過失による場合を除き、責任を負わないものとします。</p>
                </section>
                <section>
                    <h2 class="text-xl font-bold text-ink">第6条（規約の変更）</h2>
                    <p class="mt-3">当プロジェクトは、必要に応じて本規約を変更できるものとします。変更後の規約は本ページに掲載した時点で効力を生じます。</p>
                </section>
                <section>
                    <h2 class="text-xl font-bold text-ink">第7条（運営者・お問い合わせ）</h2>
                    <p class="mt-3">運営者：BuildOne（正式名称・所在地は追って記載）<br>本規約に関するお問い合わせは<a href="{{ route('contact.show') }}" class="font-medium text-brand underline underline-offset-2 hover:text-brand-dark">お問い合わせフォーム</a>までお願いいたします。</p>
                </section>
            </div>
        </div>
    </section>
@endsection
