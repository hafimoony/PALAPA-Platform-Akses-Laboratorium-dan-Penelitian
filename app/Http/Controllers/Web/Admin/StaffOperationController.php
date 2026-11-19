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
            'damage_severity'        => 'required|in:minor,moderate,severe',
            'chronology_description' => 'required|string',
            'repair_cost_estimate'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            IncidentMaintenanceTicket::create([
                'ticket_id'              => 'TKT-' . Str::upper(Str::random(8)),
                'booking_id'             => $validated['booking_id'],
                'equipment_id'           => $validated['equipment_id'] ?? null,
                'reported_by_staff_id'   => $request->user()->user_id,
                'damage_severity'        => $validated['damage_severity'],
                'chronology_description' => $validated['chronology_description'],
                'repair_cost_estimate'   => $validated['repair_cost_estimate'],
                'deduct_from_deposit'    => $request->boolean('deduct_from_deposit', true),
            ]);

            // Ubah status alat menjadi maintenance bila ada alat yang rusak
            if (!empty($validated['equipment_id'])) {
                LabEquipment::where('equipment_id', $validated['equipment_id'])
                    ->update(['status' => 'maintenance']);
            }
        });

        return back()->with('success', 'Tiket insiden kerusakan berhasil dicatat dan status alat diperbarui.');
    }

    public function processRefund(Request $request)
    {
        $validated = $request->validate([
            'booking_id'            => 'required|exists:bookings,booking_id',
            'refund_account_number' => 'required|string|max:50',
        ]);

        $booking = Booking::with('invoice')->findOrFail($validated['booking_id']);
        $invoice = $booking->invoice;

        if (!$invoice) {
            return back()->with('error', 'Invoice tidak ditemukan untuk reservasi ini.');
        }

        if (DepositRefund::where('invoice_id', $invoice->invoice_id)->exists()) {
            return back()->with('error', 'Pengembalian deposit untuk invoice ini sudah diproses.');
        }

        // Hitung potongan otomatis dari tiket insiden yang dipotongkan ke deposit
        $ticket = IncidentMaintenanceTicket::where('booking_id', $booking->booking_id)
            ->where('deduct_from_deposit', true)
            ->first();

        $originalDeposit = (float) $invoice->security_deposit_fee;
        $deduction       = $ticket ? min($originalDeposit, (float) $ticket->repair_cost_estimate) : 0.0;
        $finalRefund     = max(0, $originalDeposit - $deduction);

        $defaultVendorId = DB::table('payment_vendors')->value('vendor_id') ?? 'VND-001';

        DepositRefund::create([
            'refund_id'               => 'RFD-' . Str::upper(Str::random(8)),
            'invoice_id'              => $invoice->invoice_id,
            'ticket_id'               => $ticket?->ticket_id,
            'original_deposit_amount' => $originalDeposit,
            'deduction_amount'        => $deduction,
            'final_refund_amount'     => $finalRefund,
            'refund_vendor_id'        => $defaultVendorId,
            'refund_account_number'   => $validated['refund_account_number'],
            'refund_status'           => 'refunded',
            'processed_at'            => now(),
        ]);

        return back()->with('success', 'Pengembalian deposit sebesar Rp ' . number_format($finalRefund, 0, ',', '.') . ' berhasil diproses.');
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
