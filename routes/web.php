<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LaboratoryController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

// LUPA PASSWORD
// Route Tampilan Form Lupa Password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Route Proses Submit Form Lupa Password
Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $user = \App\Models\User::where('email', $request->email)->first();
    if (!$user) {
        return back()->withErrors(['email' => 'Email instansi tidak ditemukan dalam sistem kami.']);
    }

    return back()->with('status', 'Tautan instruksi pemulihan kata sandi telah dikirimkan ke email Anda.');
})->name('password.email');

// REGIS WOY REGIISSSS INI
// Route untuk menampilkan Form Register
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Route untuk memproses Submit Register
Route::post('/register', [AuthController::class, 'register']);

// INI LOGIN GOOGLE YA
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
