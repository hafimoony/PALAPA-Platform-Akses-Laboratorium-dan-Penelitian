<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LaboratoryController;

// Route Beranda (Menampilkan 3 Lab Random)
Route::get('/', [LaboratoryController::class, 'beranda'])->name('home');

// Route Katalog Lab Lengkap
Route::get('/labs', [LaboratoryController::class, 'index'])->name('labs.index');

// Route Detail Lab
Route::get('/labs/{id}', [LaboratoryController::class, 'show'])->name('labs.show');
