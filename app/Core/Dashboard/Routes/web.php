<?php

declare(strict_types=1);

use App\Core\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/admin', [DashboardController::class, 'index'])->name('dashboard');
