<?php

declare(strict_types=1);

use App\Features\Entry\Http\Controllers\AttendanceController;
use App\Features\Entry\Http\Controllers\KioskController;
use App\Features\Entry\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/entry')->name('entry.')->group(function (): void {
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('/export', [AttendanceController::class, 'export'])->name('export');

    Route::get('/kiosk', [KioskController::class, 'show'])->name('kiosk');
    Route::post('/punch', [KioskController::class, 'punch'])->name('punch');

    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::patch('/members/{member}/toggle', [MemberController::class, 'toggle'])->name('members.toggle');
});
