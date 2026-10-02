<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BaseTransaction;
use App\Models\BookingEquipmentItem;
use App\Models\Invoice;
use App\Models\Laboratory;
use App\Models\LabEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // 1. MEMBUAT PEMESANAN BARU (CREATE BOOKING & INVOICE)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:laboratories,lab_id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'session_time' => 'required|string',
            'total_participants' => 'required|integer|min:1',
            'proposal_file_url' => 'nullable|string',
            'need_technician_assistant' => 'boolean',
            'equipments' => 'array',
            'equipments.*.equipment_id' => 'required_with:equipments|exists:lab_equipments,equipment_id',
            'equipments.*.quantity' => 'required_with:equipments|integer|min:1'
        ]);

        $user = $request->user();

        // A. Cek Bentrokan Jadwal (Schedule Clash Validation)
        $isClash = Booking::where('lab_id', $validated['lab_id'])
            ->where('reservation_date', $validated['reservation_date'])
            ->where('session_time', $validated['session_time'])
            ->whereIn('status', ['approved', 'paid_confirmed', 'locked_unpaid'])
            ->exists();

        if ($isClash) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laboratorium pada tanggal dan sesi waktu tersebut sudah dipesan.'
            ], 422);
        }

        // B. Kalkulasi Biaya Dinamis
        $lab = Laboratory::findOrFail($validated['lab_id']);
        $labRentalFee = $lab->base_price_per_session;
        $equipmentFee = 0;
        $equipmentItemsToInsert = [];

        if (!empty($validated['equipments'])) {
            foreach ($validated['equipments'] as $item) {
                $equip = LabEquipment::findOrFail($item['equipment_id']);
                $subtotal = $equip->rental_price_per_unit * $item['quantity'];
                $equipmentFee += $subtotal;

                $equipmentItemsToInsert[] = [
                    'equipment_id' => $equip->equipment_id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $equip->rental_price_per_unit,
                    'subtotal_price' => $subtotal
                ];
            }
        }

        $technicianFee = (!empty($validated['need_technician_assistant']) && $validated['need_technician_assistant']) ? 150000.00 : 0.00;
        $securityDepositFee = 200000.00; // Deposit standar Rp 200.000
        $taxFee = ($labRentalFee + $equipmentFee + $technicianFee) * 0.11; // PPN 11%
        $vendorAdminFee = 5000.00;

        $grandTotal = $labRentalFee + $equipmentFee + $technicianFee + $securityDepositFee + $taxFee + $vendorAdminFee;

        // C. Simpan ke Database secara Atomic (Transaction)
        DB::beginTransaction();
        try {
            $transactionId = 'TRX-' . Str::upper(Str::random(10));
            $bookingCode = 'PLP-' . strtoupper(Str::random(6));
            $invoiceId = 'INV-' . Str::upper(Str::random(10));

            // 1. Insert Base Transaction (Parent)
            BaseTransaction::create([
                'transaction_id' => $transactionId,
                'transaction_number' => 'TRX/PALAPA/' . date('Ymd') . '/' . Str::random(4),
                'user_id' => $user->user_id,
                'total_amount' => $grandTotal
            ]);

            // 2. Insert Booking (Child)
            $booking = Booking::create([
                'booking_id' => $transactionId, // Inherit dari base_transactions
                'booking_code' => $bookingCode,
                'lab_id' => $validated['lab_id'],
                'reservation_date' => $validated['reservation_date'],
                'session_time' => $validated['session_time'],
                'total_participants' => $validated['total_participants'],
                'proposal_file_url' => $validated['proposal_file_url'] ?? null,
                'need_technician_assistant' => $validated['need_technician_assistant'] ?? false,
                'status' => 'pending_review'
            ]);

            // 3. Insert Booking Equipment Items
            foreach ($equipmentItemsToInsert as $item) {
                BookingEquipmentItem::create([
                    'item_id' => 'ITM-' . Str::upper(Str::random(10)),
                    'booking_id' => $transactionId,
                    'equipment_id' => $item['equipment_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal_price' => $item['subtotal_price']
                ]);
            }

            // 4. Insert Invoice
            Invoice::create([
                'invoice_id' => $invoiceId,
                'invoice_number' => 'INV/PALAPA/' . date('Ym') . '/' . rand(1000, 9999),
                'booking_id' => $transactionId,
                'lab_rental_fee' => $labRentalFee,
                'equipment_fee' => $equipmentFee,
                'technician_fee' => $technicianFee,
                'security_deposit_fee' => $securityDepositFee,
                'tax_fee' => $taxFee,
                'vendor_admin_fee' => $vendorAdminFee,
                'grand_total' => $grandTotal,
                'payment_status' => 'unpaid',
                'expired_at' => now()->addHours(24)
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan reservasi berhasil dibuat! Silakan tunggu verifikasi staf.',
                'data' => [
                    'booking_code' => $bookingCode,
                    'grand_total' => $grandTotal
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat pemesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    // 2. TAMPILKAN RIWAYAT PEMESANAN MILIK CLIENT
    public function myBookings(Request $request)
    {
        $user = $request->user();
        $bookings = Booking::with(['laboratory', 'equipmentItems', 'invoice'])
            ->whereHas('baseTransaction', function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }
}
