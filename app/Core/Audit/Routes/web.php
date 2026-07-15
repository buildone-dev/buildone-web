<?php

declare(strict_types=1);

use App\Core\Audit\Http\Controllers\AuditController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/admin/audit', [AuditController::class, 'index'])->name('audit.index');
