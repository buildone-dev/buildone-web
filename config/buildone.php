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

    // このチャンネルの約束
    'promises' => [
        'AIを使う',
        '1週間で作る',
        '本当に動くものを作る',
        'すべて公開する',
        '良かった点・失敗した点も話す',
        'ソースコードを公開（可能な範囲）',
        '実務で使えるか検証する',
    ],

    // ターゲット（こんな方へ）
    'audience' => [
        ['icon' => 'user', 'label' => '中小企業の社長'],
        ['icon' => 'users', 'label' => '現場の管理者'],
        ['icon' => 'cog', 'label' => 'DX担当者'],
    ],

    // 第1弾システムの詳細（QR・ICカード入退室管理）
    'entry' => [
        'number' => '#1',
        'name' => 'QR・ICカード入退室管理システム',
        'problems' => [
            '紙の名簿で手間がかかる',
            '入退室の履歴がわからない',
            '保護者への連絡が大変',
            '月額費用を削減したい',
        ],
        'workflow' => [
            ['step' => 1, 'title' => 'QRを読み取る', 'text' => 'スマホやリーダーでQR・ICカードをかざす。'],
            ['step' => 2, 'title' => 'ビッと登録', 'text' => '一瞬で入退室を記録。「入室しました」と表示。'],
            ['step' => 3, 'title' => '管理画面に記録', 'text' => '誰がいつ入退室したかを自動で一覧化。'],
            ['step' => 4, 'title' => '保護者へ通知', 'text' => 'LINE・メールで入退室をリアルタイム通知。'],
            ['step' => 5, 'title' => 'データを分析・活用', 'text' => '入退室人数や時間帯別グラフで見える化。'],
        ],
        'functions' => [
            'QRコード入退室', 'ICカード対応', '入退室履歴一覧', 'リアルタイム集計',
            '保護者通知（LINE/メール）', 'CSV出力', '利用者管理', '権限管理',
        ],
        'stats' => [
            ['label' => '開発期間', 'value' => '7', 'unit' => '日'],
            ['label' => 'AI利用料', 'value' => '約2,000', 'unit' => '円'],
            ['label' => '開発時間', 'value' => '約18', 'unit' => '時間'],
            ['label' => '完成度', 'value' => '82', 'unit' => '点'],
        ],
    ],

    // 開発スタック（予定）
    'stack' => [
        ['name' => 'Laravel', 'role' => 'バックエンド'],
        ['name' => 'MySQL', 'role' => 'データベース'],
        ['name' => 'Tailwind CSS', 'role' => 'フロントエンド'],
        ['name' => 'Vite', 'role' => 'ビルド'],
        ['name' => 'PWA対応', 'role' => 'スマホでも使える'],
        ['name' => 'Docker', 'role' => 'ローカル開発環境'],
    ],

];
