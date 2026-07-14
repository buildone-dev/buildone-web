<?php

/*
 * さくらのレンタルサーバー用フロントコントローラー。
 *
 * 公開フォルダ(~/www)を変更できないため、Laravel本体はWeb非公開の
 * ~/buildone-web に置き、このファイルを ~/www/index.php として配置する。
 * 標準の public/index.php との差分は、参照パスを ../buildone-web に
 * 変更した点と、公開パスを ~/www に設定する usePublicPath() の2点のみ。
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// アプリ本体の設置場所（~/www から見た相対パス）
$appRoot = __DIR__.'/../buildone-web';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appRoot.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appRoot.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appRoot.'/bootstrap/app.php';

// Vite の manifest 等を ~/www 側から解決させる
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
