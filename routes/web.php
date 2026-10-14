<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LaboratoryController;
use App\Http\Controllers\Api\AuthController;

// Route Beranda (Menampilkan 3 Lab Random)
Route::get('/', [LaboratoryController::class, 'beranda'])->name('home');

// Route Katalog Lab Lengkap
Route::get('/labs', [LaboratoryController::class, 'index'])->name('labs.index');

// Route Detail Lab
Route::get('/labs/{id}', [LaboratoryController::class, 'show'])->name('labs.show');

// Route Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Group Route yang Wajib Login Dulu
Route::middleware(['auth'])->group(function () {
    // Beranda
    Route::get('/', [LaboratoryController::class, 'beranda'])->name('home');

    // Katalog Lab
    Route::get('/labs', [LaboratoryController::class, 'index'])->name('labs.index');
    Route::get('/labs/{id}', [LaboratoryController::class, 'show'])->name('labs.show');
});
