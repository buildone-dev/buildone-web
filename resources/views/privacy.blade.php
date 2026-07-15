@extends('layouts.app')

@section('title', 'プライバシーポリシー | BuildOne')
@section('meta_description', 'BuildOneのプライバシーポリシーです。')
@section('canonical', route('privacy'))

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 sm:py-24">
            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">プライバシーポリシー</h1>
            <p class="mt-4 text-sm text-slate-500">最終更新日: 2026年7月15日</p>

            <div class="mt-10 space-y-10 leading-relaxed text-slate-700">
                <section>
                    <h2 class="text-xl font-bold text-ink">1. 基本方針</h2>
                    <p class="mt-3">
                        BuildOne（以下「当プロジェクト」）は、当サイトの利用者の個人情報を適切に取り扱い、その保護に努めます。
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-ink">2. 取得する情報と利用目的</h2>
                    <p class="mt-3">
                        当サイトでは、お問い合わせの際にお名前・メールアドレス等の情報をご提供いただく場合があります。
                        取得した情報は、お問い合わせへの回答および当プロジェクトのサービス向上のためにのみ利用し、それ以外の目的には利用しません。
                    </p>
                    <p class="mt-3">
                        また、当サイトのAIチャットでのやり取りの内容は、サービス改善およびお問い合わせ対応のために記録・保存する場合があります。
                        チャットの内容をお問い合わせに添付するかどうかは、送信時にご本人が選択できます。個人情報や機密情報の入力はお控えください。
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-ink">3. 第三者への提供</h2>
                    <p class="mt-3">
                        法令に基づく場合を除き、ご本人の同意なく個人情報を第三者に提供することはありません。
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-ink">4. アクセス解析について</h2>
                    <p class="mt-3">
                        当サイトでは、サービス改善のためにアクセス解析ツールを利用する場合があります。
                        利用する場合、収集されるデータは匿名であり、個人を特定するものではありません。
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-ink">5. 外部リンクについて</h2>
                    <p class="mt-3">
                        当サイトからリンクされた外部サイト（YouTube等）における個人情報の取り扱いについては、各サイトのプライバシーポリシーをご確認ください。
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-ink">6. 本ポリシーの変更</h2>
                    <p class="mt-3">
                        本ポリシーの内容は、必要に応じて予告なく変更されることがあります。変更後の内容は当ページに掲載した時点で効力を生じます。
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-ink">7. お問い合わせ</h2>
                    <p class="mt-3">
                        本ポリシーに関するお問い合わせは、<a href="{{ route('home') }}#contact" class="font-medium text-brand underline underline-offset-2 hover:text-brand-dark">お問い合わせ窓口</a>までお願いいたします。
                    </p>
                </section>
            </div>
        </div>
    </section>
@endsection
