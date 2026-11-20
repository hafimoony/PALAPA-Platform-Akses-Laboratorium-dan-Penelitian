<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DepositRefund;
use App\Models\IncidentMaintenanceTicket;
use App\Models\LabEntryPass;
use App\Models\LabEquipment;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaffOperationController extends Controller
{
    // 1. DASHBOARD STAFF
    public function dashboard()
    {
        $stats = [
            'total_labs'       => Laboratory::count(),
            'total_equipments' => LabEquipment::count(),
            'pending_bookings' => Booking::whereIn('status', ['pending_review', 'paid_confirmed'])->count(),
            'approved_bookings'=> Booking::where('status', 'approved')->count(),
            'total_revenue'    => DB::table('invoices')->where('payment_status', 'paid_successful')->sum('grand_total'),
        ];

        $recentBookings = Booking::with(['laboratory', 'baseTransaction.user', 'invoice'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('staff.dashboard', compact('stats', 'recentBookings'));
    }

    // 2. TRANSAKSI T2: DAFTAR & VERIFIKASI RESERVASI (ALUR 2 ROLE)
    public function bookingsIndex(Request $request)
    {
        $query = Booking::with(['laboratory', 'baseTransaction.user', 'invoice', 'entryPass']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('staff.bookings.index', compact('bookings'));
    }

    public function approveBooking(Request $request, string $booking_id)
    {
        $booking = Booking::with('invoice')->findOrFail($booking_id);

        if ($booking->status === 'approved') {
            return back()->with('error', 'Reservasi ini sudah disetujui sebelumnya.');
        }

        DB::transaction(function () use ($booking, $request) {
            $booking->update([
                'status'               => 'approved',
                'approved_by_staff_id' => $request->user()->user_id,
                'rejection_reason'     => null,
            ]);

            // Otomatis terbitkan LabEntryPass saat disetujui
            LabEntryPass::firstOrCreate(
                ['booking_id' => $booking->booking_id],
                [
                    'pass_id'        => 'PASS-' . Str::upper(Str::random(8)),
                    'qr_code_data'   => 'PALAPA-PASS-' . $booking->booking_code,
                    'checkin_status' => 'not_checked_in',
                ]
            );
        });

        return back()->with('success', 'Reservasi ' . $booking->booking_code . ' disetujui & Entry Pass berhasil diterbitkan.');
    }

    public function rejectBooking(Request $request, string $booking_id)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        $booking = Booking::findOrFail($booking_id);

        $booking->update([
            'status'               => 'rejected',
            'approved_by_staff_id' => $request->user()->user_id,
            'rejection_reason'     => $request->rejection_reason,
        ]);

        return back()->with('success', 'Reservasi ' . $booking->booking_code . ' telah ditolak.');
    }

    // 3. TRANSAKSI T4: CHECK-IN ENTRY PASS
    public function checkinIndex()
    {
        $passes = LabEntryPass::with(['booking.laboratory', 'booking.baseTransaction.user'])
            ->orderByDesc('pass_id')
            ->paginate(10);

        return view('staff.checkin', compact('passes'));
    }

    public function processCheckin(Request $request, string $pass_id)
    {
        $pass = LabEntryPass::with('booking')->findOrFail($pass_id);

        if ($pass->checkin_status === 'checked_in') {
            return back()->with('error', 'Peserta pada Entry Pass ini sudah melakukan check-in.');
        }

        DB::transaction(function () use ($pass, $request) {
            $pass->update([
                'checkin_status'       => 'checked_in',
                'checked_in_at'        => now(),
                'verified_by_staff_id' => $request->user()->user_id,
            ]);

            if ($pass->booking) {
                $pass->booking->update(['status' => 'completed']);
            }
        });

        return back()->with('success', 'Check-in berhasil diverifikasi untuk kode ' . $pass->qr_code_data . '.');
    }

    // 4. TRANSAKSI T5: PELAPORAN INSIDEN KERUSAKAN & PENGEMBALIAN DEPOSIT
    public function incidentsIndex()
    {
        $bookings   = Booking::with(['laboratory', 'invoice'])->whereIn('status', ['approved', 'completed'])->get();
        $equipments = LabEquipment::with('laboratory')->get();
        $tickets    = IncidentMaintenanceTicket::orderByDesc('ticket_id')->get();
        $refunds    = DepositRefund::orderByDesc('refund_id')->get();

        return view('staff.incidents', compact('bookings', 'equipments', 'tickets', 'refunds'));
    }

    public function storeIncident(Request $request)
    {
        $validated = $request->validate([
            'booking_id'             => 'required|exists:bookings,booking_id',
            'equipment_id'           => 'nullable|exists:lab_equipments,equipment_id',
            'severity'               => 'nullable|in:minor,moderate,severe',
            'damage_severity'        => 'nullable|in:minor,moderate,severe',
            'description'            => 'nullable|string',
            'chronology_description' => 'nullable|string',
            'repair_cost_estimate'   => 'required|numeric|min:0',
        ]);

        $severity = $request->input('severity') ?? $request->input('damage_severity') ?? 'minor';
        $description = $request->input('description') ?? $request->input('chronology_description') ?? '-';

        DB::transaction(function () use ($validated, $request, $severity, $description) {
            IncidentMaintenanceTicket::create([
                'ticket_id'                     => 'TKT-' . Str::upper(Str::random(8)),
                'booking_id'                    => $validated['booking_id'],
                'equipment_id'                  => $validated['equipment_id'] ?? null,
                'reported_by_staff_id'          => $request->user()->user_id,
                'severity'                      => $severity,
                'description'                   => $description,
                'repair_cost_estimate'          => $validated['repair_cost_estimate'],
                'penalty_deducted_from_deposit' => $validated['repair_cost_estimate'],
                'status'                        => 'open',
            ]);

            // Ubah status alat menjadi maintenance bila ada alat yang rusak dan tingkat kerusakan moderate/severe
            if (!empty($validated['equipment_id']) && in_array($severity, ['moderate', 'severe'])) {
                LabEquipment::where('equipment_id', $validated['equipment_id'])
                    ->update(['status' => 'maintenance']);
            }
        });

        return back()->with('success', 'Tiket insiden kerusakan berhasil dicatat dan status alat diperbarui.');
    }

    public function processRefund(Request $request)
    {
        $validated = $request->validate([
            'booking_id'       => 'required|exists:bookings,booking_id',
            'deduction_amount' => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);

        $booking = Booking::with('invoice')->findOrFail($validated['booking_id']);
        $invoice = $booking->invoice;

        if (DepositRefund::where('booking_id', $booking->booking_id)->exists()) {
            return back()->with('error', 'Pengembalian deposit untuk reservasi ini sudah diproses.');
        }

        $originalDeposit = (float) ($invoice?->security_deposit_fee ?? 200000);
        $deduction       = (float) ($validated['deduction_amount'] ?? 0);
        $refundedAmount  = max(0, $originalDeposit - $deduction);

        $notes = $validated['notes'] ?? null;
        if ($deduction > $originalDeposit) {
            $selisih = $deduction - $originalDeposit;
            $catatanSelisih = "Biaya kerusakan (Rp " . number_format($deduction, 0, ',', '.') . ") melebihi deposit (Rp " . number_format($originalDeposit, 0, ',', '.') . "). Tagihan selisih ke penyewa: Rp " . number_format($selisih, 0, ',', '.');
            $notes = $notes ? $notes . " | " . $catatanSelisih : $catatanSelisih;
        }

        DepositRefund::create([
            'refund_id'             => 'RFD-' . Str::upper(Str::random(8)),
            'booking_id'            => $booking->booking_id,
            'processed_by_staff_id' => $request->user()->user_id,
            'original_deposit'      => $originalDeposit,
            'deduction_amount'      => $deduction,
            'refunded_amount'       => $refundedAmount,
            'refund_status'         => 'refunded',
            'notes'                 => $notes,
            'refunded_at'           => now(),
        ]);

        return back()->with('success', 'Pengembalian deposit sebesar Rp ' . number_format($refundedAmount, 0, ',', '.') . ' berhasil diproses.');
    }

    // 5. LAPORAN / REKAP QUERY BUILDER (JOIN + GROUP BY + COUNT & SUM)
    public function reportsIndex(Request $request)
    {
        $query = DB::table('laboratories as l')
            ->join('campuses as c', 'l.campus_id', '=', 'c.campus_id')
            ->leftJoin('bookings as b', 'l.lab_id', '=', 'b.lab_id')
            ->leftJoin('invoices as i', function ($join) {
                $join->on('b.booking_id', '=', 'i.booking_id')
                     ->where('i.payment_status', '=', 'paid_successful');
            })
            ->select(
                'l.lab_id',
                'l.lab_name',
                'l.category',
                'c.campus_name',
                DB::raw('COUNT(b.booking_id) as total_reservasi'),
                DB::raw('COALESCE(SUM(i.grand_total), 0) as total_pendapatan')
            )
            ->groupBy('l.lab_id', 'l.lab_name', 'l.category', 'c.campus_name');

        if ($request->filled('campus_id')) {
            $query->where('l.campus_id', $request->campus_id);
        }

        $rekap = $query->orderByDesc('total_pendapatan')->get();
        $campuses = DB::table('campuses')->orderBy('campus_name')->get();

        return view('staff.reports', compact('rekap', 'campuses'));
    }
}
