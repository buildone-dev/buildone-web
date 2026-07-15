<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/privacy', 'privacy')->name('privacy');

// UI Kit プレビュー（開発用。本番では公開しない）
if (! app()->isProduction()) {
    Route::view('/ui-kit', 'pages.ui-kit')->name('ui-kit');
}
