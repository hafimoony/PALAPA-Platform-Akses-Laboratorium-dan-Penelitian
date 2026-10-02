<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\LabEntryPass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EntryPassController extends Controller
{
    // GET /api/entry-pass/{booking_id} (Client melihat/mengunduh Pass Masuk)
    public function show($booking_id)
    {
        $booking = Booking::where('booking_id', $booking_id)
            ->orWhere('booking_code', $booking_id)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        }

        if ($booking->status !== 'paid_confirmed') {
            return response()->json(['message' => 'Pass masuk hanya tersedia untuk pemesanan yang sudah dibayar Lunas'], 400);
        }

        // Generate Pass Masuk jika belum dibuat
        $pass = LabEntryPass::firstOrCreate(
            ['booking_id' => $booking->booking_id],
            [
                'pass_id' => 'PASS-' . strtoupper(Str::random(10)),
                'qr_code_data' => json_encode([
                    'booking_code' => $booking->booking_code,
                    'lab_id' => $booking->lab_id,
                    'reservation_date' => $booking->reservation_date,
                    'valid_until' => $booking->reservation_date . ' ' . $booking->session_time
                ]),
                'checkin_status' => 'not_checked_in'
            ]
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'pass_id' => $pass->pass_id,
                'booking_code' => $booking->booking_code,
                'qr_code_data' => json_decode($pass->qr_code_data),
                'checkin_status' => $pass->checkin_status,
                'checked_in_at' => $pass->checked_in_at
            ]
        ]);
    }

    // POST /api/staff/checkin (Staf memverifikasi/scan QR Code saat penyewa tiba)
    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'booking_code' => 'required|string'
        ]);

        $booking = Booking::where('booking_code', $validated['booking_code'])->first();

        if (!$booking) {
            return response()->json(['message' => 'Kode booking tidak valid'], 404);
        }

        $pass = LabEntryPass::where('booking_id', $booking->booking_id)->first();

        if (!$pass) {
            return response()->json(['message' => 'Pass masuk belum diterbitkan untuk pemesanan ini'], 404);
        }

        if ($pass->checkin_status === 'checked_in') {
            return response()->json(['message' => 'Penyewa sudah melakukan check-in sebelumnya pada ' . $pass->checked_in_at], 400);
        }

        // Update status check-in pass masuk
        $pass->update([
            'checkin_status' => 'checked_in',
            'checked_in_at' => now(),
            'verified_by_staff_id' => $request->user()->user_id ?? null
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in berhasil! Akses ke laboratorium telah diverifikasi.',
            'data' => $pass
        ]);
    }
}
