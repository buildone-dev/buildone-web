<?php

declare(strict_types=1);

use App\Core\Settings\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/settings')->name('settings.')->group(function (): void {
    Route::get('/', [SettingsController::class, 'edit'])->name('edit');
    Route::put('/', [SettingsController::class, 'update'])->name('update');
});
