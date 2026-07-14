<?php

/*
|--------------------------------------------------------------------------
| BuildOne サイト設定
|--------------------------------------------------------------------------
| トップページのコンテンツを一元管理する。初期版はデータベースを使わず、
| この配列を編集するだけでカードやリンクを追加・変更できる。
*/

return [

    'tagline' => '社長、そのシステム作れます。',
    'subcopy' => 'AIで会社の業務システムを1週間で開発・公開。',

    'youtube' => [
        'channel_name' => 'BuildOne | 社長、そのシステム作れます。',
        'handle' => '@buildonejp',
        // 未設定（null/空）の間はリンクを張らずハンドル表示のみ
        'url' => env('BUILDONE_YOUTUBE_URL') ?: null,
        // 未設定の間は「近日公開」プレースホルダーを表示
        'video_url' => env('BUILDONE_YOUTUBE_VIDEO_URL') ?: null,
    ],

    // どちらかが設定されると問い合わせボタンが有効になる
    'contact' => [
        'url' => env('CONTACT_URL') ?: null,
        'email' => env('CONTACT_EMAIL') ?: null,
    ],

    // 開発システム一覧（カードは配列を足すだけで増える）
    'systems' => [
        [
            'name' => 'BuildOne Entry',
            'description' => 'QR・ICカード入退室管理',
            'status' => '第1弾・開発予定',
            'featured' => true,
        ],
        [
            'name' => 'BuildOne Time',
            'description' => '勤怠管理',
            'status' => 'Coming Soon',
            'featured' => false,
        ],
        [
            'name' => 'BuildOne Shift',
            'description' => 'シフト管理',
            'status' => 'Coming Soon',
            'featured' => false,
        ],
        [
            'name' => 'BuildOne Reserve',
            'description' => '予約管理',
            'status' => 'Coming Soon',
            'featured' => false,
        ],
        [
            'name' => 'BuildOne Report',
            'description' => 'AI報告書・日報',
            'status' => 'Coming Soon',
            'featured' => false,
        ],
    ],

    // BuildOneの特徴
    'features' => [
        ['icon' => 'sparkles', 'title' => 'AIと人間で開発', 'text' => 'AIの開発力と人間の現場理解を組み合わせて、スピードと品質を両立します。'],
        ['icon' => 'bolt', 'title' => '1週間単位で挑戦', 'text' => '企画から公開まで1週間。短いサイクルで作り、素早く検証します。'],
        ['icon' => 'cog', 'title' => '実際に動くところまで制作', 'text' => 'モックで終わらせず、実環境で動作するシステムとして仕上げます。'],
        ['icon' => 'camera', 'title' => '成功も失敗も公開', 'text' => '開発の過程を、うまくいかなかった部分も含めてYouTubeで公開します。'],
        ['icon' => 'check', 'title' => '現場で使えるか検証', 'text' => '実際の業務を想定したテストを行い、現場で使える品質かを確かめます。'],
        ['icon' => 'chat', 'title' => '相談から個別開発へ対応予定', 'text' => '視聴者や企業からの相談をもとに、個別の開発依頼にも対応していく予定です。'],
    ],

    // 開発の流れ
    'process' => [
        ['step' => 1, 'title' => '業界と課題を調査', 'text' => '現場のヒアリングや調査で、本当に困っている課題を見つけます。'],
        ['step' => 2, 'title' => '必要な機能を設計', 'text' => '課題を解決する最小限の機能に絞って設計します。'],
        ['step' => 3, 'title' => 'AIと開発', 'text' => 'AIを活用して、設計から実装までを高速に進めます。'],
        ['step' => 4, 'title' => '実環境でテスト', 'text' => '実際の利用シーンを想定した環境で動作を確認します。'],
        ['step' => 5, 'title' => 'YouTubeで公開', 'text' => '開発の過程と結果を動画で公開します。'],
        ['step' => 6, 'title' => '利用者の声で改善', 'text' => 'フィードバックを受けて改善を続けます。'],
    ],

];
