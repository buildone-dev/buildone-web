<?php

declare(strict_types=1);

use App\Features\Report\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/report')->name('report.')->group(function (): void {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/export', [ReportController::class, 'export'])->name('export');
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/', [ReportController::class, 'store'])->name('store');
    Route::get('/{report}', [ReportController::class, 'show'])->name('show');
    Route::get('/{report}/edit', [ReportController::class, 'edit'])->name('edit');
    Route::put('/{report}', [ReportController::class, 'update'])->name('update');
    Route::post('/{report}/summarize', [ReportController::class, 'summarize'])->name('summarize');
});
