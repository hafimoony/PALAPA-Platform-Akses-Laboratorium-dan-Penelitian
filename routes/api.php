<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaboratoryController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\StaffBookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\EntryPassController;
use App\Http\Controllers\Api\IncidentRefundController;

/*
|--------------------------------------------------------------------------
| API Routes - Palapa Lab Reservation Backend
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES (Tanpa Autentikasi Token) ---
Route::match(['get', 'post'], '/register', [AuthController::class, 'register']);
Route::match(['get', 'post'], '/login', [AuthController::class, 'login']);

Route::get('/labs', [LaboratoryController::class, 'index']);
Route::get('/labs/{id}', [LaboratoryController::class, 'show']);


// --- PROTECTED ROUTES (Wajib Kirim Header: Authorization: Bearer <token>) ---
Route::middleware('auth:sanctum')->group(function () {

    // Auth & User Profile
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Client Booking Module
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);

    // Staff & Admin Verification Module
    Route::get('/staff/bookings', [StaffBookingController::class, 'index']);
    Route::patch('/staff/bookings/{id}/approve', [StaffBookingController::class, 'approve']);
    Route::patch('/staff/bookings/{id}/reject', [StaffBookingController::class, 'reject']);

    // Payment Module
    Route::get('/payments/vendors', [PaymentController::class, 'getVendors']);
    Route::post('/payments/pay', [PaymentController::class, 'payInvoice']);

    // Entry Pass Module
    Route::get('/entry-pass/{booking_id}', [EntryPassController::class, 'show']);
    Route::post('/staff/checkin', [EntryPassController::class, 'checkIn']);

    // Incident & Refund Module
    Route::post('/staff/incidents', [IncidentRefundController::class, 'reportIncident']);
    Route::post('/staff/process-refund', [IncidentRefundController::class, 'processRefund']);
});
