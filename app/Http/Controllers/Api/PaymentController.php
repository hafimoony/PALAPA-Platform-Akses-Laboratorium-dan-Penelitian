<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // GET /api/payments/vendors (Melihat daftar vendor pembayaran aktif)
    public function getVendors()
    {
        $vendors = PaymentVendor::where('is_active', true)->get();

        return response()->json([
            'status' => 'success',
            'data' => $vendors
        ]);
    }

    // POST /api/payments/pay (Simulasi Pembayaran Invoice)
    public function payInvoice(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|string',
            'vendor_id' => 'required|string',
            'paid_amount' => 'required|numeric|min:0',
            'payment_proof_url' => 'nullable|string'
        ]);

        return DB::transaction(function () use ($validated) {
            // Cari Booking & Invoice
            $booking = Booking::with('invoice')
                ->where('booking_id', $validated['booking_id'])
                ->orWhere('booking_code', $validated['booking_id'])
                ->first();

            if (!$booking) {
                return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
            }

            if ($booking->status !== 'approved') {
                return response()->json(['message' => 'Hanya pemesanan berstatus approved yang bisa dibayar'], 400);
            }

            $invoice = $booking->invoice;

            if (!$invoice) {
                return response()->json(['message' => 'Invoice tidak ditemukan'], 404);
            }

            if ($invoice->payment_status === 'paid_successful') {
                return response()->json(['message' => 'Invoice ini sudah lunas'], 400);
            }

            // Validasi jumlah yang dibayar
            if ($validated['paid_amount'] < $invoice->grand_total) {
                return response()->json([
                    'message' => 'Jumlah pembayaran kurang dari total invoice (Rp ' . number_format($invoice->grand_total, 0, ',', '.') . ')'
                ], 422);
            }

            // 1. Catat Transaksi Pembayaran
            $payment = Payment::create([
                'payment_id' => 'PAY-' . strtoupper(Str::random(10)),
                'invoice_id' => $invoice->invoice_id,
                'vendor_id' => $validated['vendor_id'],
                'gateway_transaction_id' => 'GATEWAY-' . time(),
                'paid_amount' => $validated['paid_amount'],
                'payment_proof_url' => $validated['payment_proof_url'] ?? null,
                'paid_at' => now()
            ]);

            // 2. Update Status Invoice
            $invoice->update([
                'payment_status' => 'paid_successful'
            ]);

            // 3. Update Status Booking
            $booking->update([
                'status' => 'paid_confirmed'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil dikonfirmasi! Status reservasi Anda kini paid_confirmed.',
                'data' => [
                    'payment' => $payment,
                    'invoice' => $invoice,
                    'booking_status' => $booking->status
                ]
            ], 201);
        });
    }
}
