<?php

declare(strict_types=1);

use App\Features\Shift\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/shift')->name('shift.')->group(function (): void {
    Route::get('/', [ShiftController::class, 'index'])->name('index');
    Route::get('/export', [ShiftController::class, 'export'])->name('export');
    Route::get('/create', [ShiftController::class, 'create'])->name('create');
    Route::post('/', [ShiftController::class, 'store'])->name('store');
    Route::get('/{shift}/edit', [ShiftController::class, 'edit'])->name('edit');
    Route::put('/{shift}', [ShiftController::class, 'update'])->name('update');
    Route::delete('/{shift}', [ShiftController::class, 'destroy'])->name('destroy');
});
