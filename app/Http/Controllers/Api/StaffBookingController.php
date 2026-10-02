<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class StaffBookingController extends Controller
{
    // GET /api/staff/bookings
    public function index(Request $request)
    {
        $query = Booking::with(['laboratory', 'equipmentItems.equipment', 'invoice']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('reservation_date', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }

    // PATCH /api/staff/bookings/{id}/approve
    public function approve(Request $request, $id)
    {
        // Cari berdasarkan booking_id ATAU booking_code
        $booking = Booking::with('invoice')
            ->where('booking_id', $id)
            ->orWhere('booking_code', $id)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Data pemesanan tidak ditemukan'], 404);
        }

        if ($booking->status !== 'pending_review') {
            return response()->json(['message' => 'Hanya pemesanan berstatus pending_review yang bisa disetujui'], 400);
        }

        // Update status booking dan staff pemicu
        $booking->update([
            'status' => 'approved',
            'approved_by_staff_id' => $request->user()->user_id ?? null
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pemesanan berhasil disetujui! Status berubah menjadi approved.',
            'data' => $booking
        ]);
    }

    // PATCH /api/staff/bookings/{id}/reject
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:5'
        ]);

        $booking = Booking::where('booking_id', $id)
            ->orWhere('booking_code', $id)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Data pemesanan tidak ditemukan'], 404);
        }

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by_staff_id' => $request->user()->user_id ?? null
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pemesanan telah ditolak.',
            'data' => $booking
        ]);
    }
}
