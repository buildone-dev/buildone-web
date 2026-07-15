<?php

declare(strict_types=1);

use App\Features\Time\Http\Controllers\EmployeeController;
use App\Features\Time\Http\Controllers\TimeClockController;
use App\Features\Time\Http\Controllers\TimeRecordController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/time')->name('time.')->group(function (): void {
    Route::get('/', [TimeRecordController::class, 'index'])->name('index');
    Route::get('/monthly', [TimeRecordController::class, 'monthly'])->name('monthly');
    Route::get('/export', [TimeRecordController::class, 'export'])->name('export');

    Route::get('/kiosk', [TimeClockController::class, 'show'])->name('kiosk');
    Route::post('/punch', [TimeClockController::class, 'punch'])->name('punch');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::patch('/employees/{employee}/toggle', [EmployeeController::class, 'toggle'])->name('employees.toggle');
});
