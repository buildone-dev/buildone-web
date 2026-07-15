<?php

declare(strict_types=1);

use App\Features\Contact\Http\Controllers\ContactController;
use App\Features\Contact\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');

Route::middleware('auth')->get('/admin/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
