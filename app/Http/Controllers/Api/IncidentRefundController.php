<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DepositRefund;
use App\Models\IncidentMaintenanceTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IncidentRefundController extends Controller
{
    // POST /api/staff/incidents (Staf melaporkan kerusakan alat)
    public function reportIncident(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|string',
            'equipment_id' => 'required|string',
            'damage_severity' => 'required|in:rusak_ringan,rusak_sedang,rusak_berat_hilang',
            'chronology_description' => 'required|string',
            'evidence_photo_url' => 'nullable|string',
            'repair_cost_estimate' => 'required|numeric|min:0'
        ]);

        $booking = Booking::where('booking_id', $validated['booking_id'])
            ->orWhere('booking_code', $validated['booking_id'])
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        }

        $ticket = IncidentMaintenanceTicket::create([
            'ticket_id' => 'TCK-' . strtoupper(Str::random(10)),
            'booking_id' => $booking->booking_id,
            'equipment_id' => $validated['equipment_id'],
            'reported_by_staff_id' => $request->user()->user_id ?? 'USR-STAFF-01',
            'damage_severity' => $validated['damage_severity'],
            'chronology_description' => $validated['chronology_description'],
            'evidence_photo_url' => $validated['evidence_photo_url'] ?? null,
            'repair_cost_estimate' => $validated['repair_cost_estimate'],
            'deduct_from_deposit' => true
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan kerusakan alat berhasil dicatat.',
            'data' => $ticket
        ], 201);
    }

    // POST /api/staff/process-refund (Staf memproses refund deposit ke penyewa)
    public function processRefund(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|string',
            'refund_account_number' => 'required|string'
        ]);

        $booking = Booking::with(['invoice'])->where('booking_id', $validated['booking_id'])
            ->orWhere('booking_code', $validated['booking_id'])
            ->first();

        if (!$booking || !$booking->invoice) {
            return response()->json(['message' => 'Invoice pemesanan tidak ditemukan'], 404);
        }

        $invoice = $booking->invoice;
        $originalDeposit = $invoice->security_deposit_fee;

        // Cek jika ada tiket insiden
        $incident = IncidentMaintenanceTicket::where('booking_id', $booking->booking_id)->first();
        $deduction = $incident ? $incident->repair_cost_estimate : 0.00;
        $finalRefund = max(0, $originalDeposit - $deduction);

        $refund = DepositRefund::create([
            'refund_id' => 'RFD-' . strtoupper(Str::random(10)),
            'invoice_id' => $invoice->invoice_id,
            'ticket_id' => $incident->ticket_id ?? null,
            'original_deposit_amount' => $originalDeposit,
            'deduction_amount' => $deduction,
            'final_refund_amount' => $finalRefund,
            'refund_account_number' => $validated['refund_account_number'],
            'refund_status' => 'processed_paid',
            'processed_at' => now()
        ]);

        // Selesaikan booking
        $booking->update(['status' => 'finished']);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengembalian deposit berhasil diproses. Status pemesanan kini finished.',
            'data' => $refund
        ], 201);
    }
}
