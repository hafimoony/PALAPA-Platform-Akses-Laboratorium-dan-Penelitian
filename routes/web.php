<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LaboratoryController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\Admin\CampusController;
use App\Http\Controllers\Web\Admin\LaboratoryController as AdminLaboratoryController;
use App\Http\Controllers\Web\Admin\LabEquipmentController;
use App\Http\Controllers\Web\Admin\StaffOperationController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use App\Models\User;

// ===================================================
// 1. ROUTE PUBLIK (Bisa diakses siapa saja)
// ===================================================
Route::get('/', [LaboratoryController::class, 'beranda'])->name('home');
Route::get('/labs', [LaboratoryController::class, 'index'])->name('labs.index');
Route::get('/labs/{id}', [LaboratoryController::class, 'show'])->name('labs.show');
// Route Katalog Instrumen Riset (Publik)
Route::get('/instrumen', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\LabEquipment::with(['laboratory.campus']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('equipment_name', 'ilike', "%{$search}%")
              ->orWhere('brand_model', 'ilike', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $equipments = $query->orderBy('equipment_name')->get();

    return view('instruments.index', compact('equipments'));
})->name('instruments.index');
Route::get('/pusat-bantuan', function () {
    return view('help.faq');
})->name('bantuan');
Route::get('/live-chat', function () {
    return view('client.chat');
})->name('client.chat.index');


// ===================================================
// 2. ROUTE AUTENTIKASI (Hanya untuk Guest / Belum Login)
// ===================================================
Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email instansi tidak ditemukan dalam sistem kami.']);
        }
        return back()->with('status', 'Tautan instruksi pemulihan kata sandi telah dikirimkan ke email Anda.');
    })->name('password.email');

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});


// ===================================================
// 3. ROUTE WAJIB LOGIN (Semua Role yang Sudah Autentikasi)
// ===================================================
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Area Khusus Client / Penyewa (Role 2)
    Route::middleware(['role:2'])->group(function () {
        Route::get('/riwayat', function (\Illuminate\Http\Request $request) {
            $bookings = \App\Models\Booking::with(['laboratory.campus', 'equipmentItems.equipment', 'invoice', 'entryPass', 'depositRefund'])
                ->whereHas('baseTransaction', function ($q) use ($request) {
                    $q->where('user_id', $request->user()->user_id);
                })
                ->orderByDesc('created_at')
                ->get();

            return view('client.riwayat', compact('bookings'));
        })->name('riwayat');

        Route::post('/bookings/{booking_code}/reschedule', function (\Illuminate\Http\Request $request, string $booking_code) {
            $request->validate([
                'reservation_date' => 'required|date|after_or_equal:today',
                'session_time'     => 'required|string|max:100',
            ]);

            $booking = \App\Models\Booking::where('booking_code', $booking_code)
                ->whereHas('baseTransaction', fn($q) => $q->where('user_id', $request->user()->user_id))
                ->firstOrFail();

            $booking->update([
                'reservation_date' => $request->reservation_date,
                'session_time'     => $request->session_time,
                'status'           => 'paid_confirmed',
                'rejection_reason' => null,
            ]);

            return back()->with('success', "Jadwal reservasi {$booking_code} berhasil diajukan ulang (Reschedule) dan menunggu verifikasi Staff.");
        })->name('client.bookings.reschedule');

        Route::get('/profil', function () {
            return view('client.profile');
        })->name('client.profile');

        Route::post('/profil/update', function (\Illuminate\Http\Request $request) {
            $user = $request->user();
            $validated = $request->validate([
                'name' => 'required|string|max:120',
                'phone_number' => 'nullable|string|max:30',
            ]);

            $user->update($validated);
            return back()->with('success', 'Data profil akun berhasil diperbarui.');
        })->name('client.profile.update');

        Route::post('/profil/password', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = $request->user();
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password_hash)) {
                return back()->withErrors(['current_password' => 'Kata sandi lama tidak sesuai.']);
            }

            $user->update([
                'password_hash' => \Illuminate\Support\Facades\Hash::make($request->new_password),
            ]);

            return back()->with('success', 'Kata sandi akun berhasil diubah.');
        })->name('client.profile.password');

        Route::get('/bookings/{booking_code}/invoice', function (\Illuminate\Http\Request $request, string $booking_code) {
            $booking = \App\Models\Booking::with(['laboratory.campus', 'equipmentItems.equipment', 'invoice', 'baseTransaction.user'])
                ->where('booking_code', $booking_code)
                ->whereHas('baseTransaction', fn($q) => $q->where('user_id', $request->user()->user_id))
                ->firstOrFail();

            return view('client.invoice', compact('booking'));
        })->name('client.invoice');

        Route::get('/bookings/{booking_code}/entry-pass', function (\Illuminate\Http\Request $request, string $booking_code) {
            $booking = \App\Models\Booking::with(['laboratory.campus', 'entryPass', 'baseTransaction.user'])
                ->where('booking_code', $booking_code)
                ->whereHas('baseTransaction', fn($q) => $q->where('user_id', $request->user()->user_id))
                ->firstOrFail();

            return view('client.entry-pass', compact('booking'));
        })->name('client.entry-pass');

        Route::get('/bookings/step1/{lab_id}', [BookingController::class, 'step1'])->name('bookings.step1');
        Route::get('/bookings/step2/{lab_id}', [BookingController::class, 'step2'])->name('bookings.step2');
        Route::get('/bookings/step3/{lab_id}', [BookingController::class, 'step3'])->name('bookings.step3');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::post('/bookings/{booking_code}/check-payment', [BookingController::class, 'checkPayment'])->name('bookings.check-payment');

        Route::get('/checkout/{booking_code?}', function (\Illuminate\Http\Request $request, ?string $booking_code = null) {
            $query = \App\Models\Booking::with(['laboratory.campus', 'equipmentItems.equipment', 'invoice'])
                ->whereHas('baseTransaction', fn($q) => $q->where('user_id', $request->user()->user_id));

            $booking = $booking_code
                ? $query->where('booking_code', $booking_code)->firstOrFail()
                : $query->orderByDesc('created_at')->first();

            $vendors = \Illuminate\Support\Facades\DB::table('payment_vendors')
                ->where('is_active', true)
                ->orderBy('vendor_id')
                ->get();

            return view('client.checkout', compact('booking', 'vendors'));
        })->name('client.checkout');
    });

    // Area Khusus Staff / Laboran (Role 1)
    Route::middleware(['role:1'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/calendar', function (\Illuminate\Http\Request $request) {
            $month = $request->input('month', now()->format('Y-m'));
            $start = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
            $end = $start->copy()->endOfMonth();

            $bookings = \App\Models\Booking::with(['laboratory.campus', 'baseTransaction.user'])
                ->whereBetween('reservation_date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('reservation_date')
                ->orderBy('session_time')
                ->get();

            return view('staff.calendar', compact('bookings', 'month', 'start'));
        })->name('calendar');

        Route::get('/lab-management', function () {
            $laboratories = \App\Models\Laboratory::with(['campus', 'equipments'])
                ->withCount(['bookings' => function ($q) {
                    $q->whereIn('status', ['paid_confirmed', 'approved', 'completed']);
                }])
                ->orderBy('lab_name')
                ->get();

            return view('staff.lab-management', compact('laboratories'));
        })->name('lab-management');

        Route::post('/lab-management/{id}/toggle-status', function (string $id) {
            $lab = \App\Models\Laboratory::findOrFail($id);
            $newStatus = ($lab->operational_status ?? 'active') === 'active' ? 'maintenance' : 'active';
            $lab->update(['operational_status' => $newStatus]);

            return back()->with('success', "Status operasional {$lab->lab_name} diubah menjadi {$newStatus}.");
        })->name('lab-management.toggle');

        Route::get('/profile', function () {
            return view('staff.profile');
        })->name('profile');

        Route::post('/profile/update', function (\Illuminate\Http\Request $request) {
            $user = $request->user();
            $validated = $request->validate([
                'name' => 'required|string|max:120',
                'phone_number' => 'nullable|string|max:30',
            ]);

            $user->update($validated);
            return back()->with('success', 'Data profil Staff berhasil diperbarui.');
        })->name('profile.update');

        Route::post('/profile/password', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = $request->user();
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password_hash)) {
                return back()->withErrors(['current_password' => 'Kata sandi lama tidak sesuai.']);
            }

            $user->update([
                'password_hash' => \Illuminate\Support\Facades\Hash::make($request->new_password),
            ]);

            return back()->with('success', 'Kata sandi Staff berhasil diubah.');
        })->name('profile.password');

        // Dashboard Staff
        Route::get('/dashboard', [StaffOperationController::class, 'dashboard'])->name('dashboard');

        // 3 Data Master (CRUD)
        Route::resource('campuses', CampusController::class)->except(['show']);
        Route::resource('laboratories', AdminLaboratoryController::class)->except(['show']);
        Route::resource('equipments', LabEquipmentController::class)->except(['show']);

        // Transaksi T2: Verifikasi & Persetujuan Reservasi (Alur 2 Role)
        Route::get('/bookings', [StaffOperationController::class, 'bookingsIndex'])->name('bookings.index');
        Route::post('/bookings/{booking_id}/approve', [StaffOperationController::class, 'approveBooking'])->name('bookings.approve');
        Route::post('/bookings/{booking_id}/reject', [StaffOperationController::class, 'rejectBooking'])->name('bookings.reject');

        // Konsultasi Riset & Live Chat Operator
        Route::get('/live-chat', function () {
            return view('staff.chat');
        })->name('chat.index');

        // Transaksi T4: Check-In Entry Pass
        Route::get('/checkin', [StaffOperationController::class, 'checkinIndex'])->name('checkin.index');
        Route::post('/checkin/{pass_id}', [StaffOperationController::class, 'processCheckin'])->name('checkin.process');

        // Transaksi T5: Insiden Kerusakan & Pengembalian Deposit
        Route::get('/incidents', [StaffOperationController::class, 'incidentsIndex'])->name('incidents.index');
        Route::post('/incidents', [StaffOperationController::class, 'storeIncident'])->name('incidents.store');
        Route::post('/refunds', [StaffOperationController::class, 'processRefund'])->name('refunds.store');

        // Laporan Rekap Query Builder
        Route::get('/reports', [StaffOperationController::class, 'reportsIndex'])->name('reports.index');
    });
});
