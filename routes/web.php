<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ManufacturerRequestController;

Route::get('/request/confirm/{id}', [ManufacturerRequestController::class, 'confirm']);
Route::get('/request/reject/{id}', [ManufacturerRequestController::class, 'reject']);



Route::get('/{any}', function () {
    return view('app');// gọi file home.blade.php
})->where('any','.*');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
