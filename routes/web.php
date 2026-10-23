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

// ===================================================
// 1. ROUTE PUBLIK (Bisa diakses siapa saja)
// ===================================================

// Route Beranda
Route::get('/', [LaboratoryController::class, 'beranda'])->name('home');

// Route Katalog & Detail Lab
Route::get('/labs', [LaboratoryController::class, 'index'])->name('labs.index');
Route::get('/labs/{id}', [LaboratoryController::class, 'show'])->name('labs.show');

// Route Pusat Bantuan
Route::get('/pusat-bantuan', function () {
    return view('help.faq');
})->name('bantuan');


// ===================================================
// 2. ROUTE AUTENTIKASI (Login, Register, Lupa Password)
// ===================================================

// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Register
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Lupa Password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $user = \App\Models\User::where('email', $request->email)->first();
    if (!$user) {
        return back()->withErrors(['email' => 'Email instansi tidak ditemukan dalam sistem kami.']);
    }

    return back()->with('status', 'Tautan instruksi pemulihan kata sandi telah dikirimkan ke email Anda.');
})->name('password.email');

// Login Google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);


// ===================================================
// 3. ROUTE WAJIB LOGIN (Hanya untuk user terdaftar)
// ===================================================

Route::middleware(['auth'])->group(function () {
    // Route Riwayat Reservasi (Untuk icon keranjang di Navbar)
    Route::get('/riwayat', function () {
        return view('client.riwayat');
    })->name('riwayat');

    // Tambahkan rute lain yang wajib login di sini (misal: checkout, profil)

    Route::get('/bookings/step1/{lab_id}', [App\Http\Controllers\Web\BookingController::class, 'step1'])->name('bookings.step1');
});
