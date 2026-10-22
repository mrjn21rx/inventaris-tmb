<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // TAMBAHKAN BARIS INI:
    Route::get('/items/export-pdf', [ItemController::class, 'exportPdf'])->name('items.pdf');
    Route::get('/items/export-excel', [ItemController::class, 'exportExcel'])->name('items.excel');
    Route::resource('items', ItemController::class);
    Route::resource('users', UserController::class)->except(['show', 'edit', 'update']);
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
    Route::get('/activity-logs', [App\Http\Controllers\LogController::class, 'index'])->name('logs.index');
    
});

require __DIR__.'/auth.php';
