<?php

declare(strict_types=1);

use App\Features\Reserve\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/reserve')->name('reserve.')->group(function (): void {
    Route::get('/', [ReservationController::class, 'index'])->name('index');
    Route::get('/export', [ReservationController::class, 'export'])->name('export');
    Route::get('/create', [ReservationController::class, 'create'])->name('create');
    Route::post('/', [ReservationController::class, 'store'])->name('store');
    Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('edit');
    Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
    Route::patch('/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('confirm');
    Route::patch('/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('cancel');
});
