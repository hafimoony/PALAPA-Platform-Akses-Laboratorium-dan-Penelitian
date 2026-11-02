<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BaseTransaction;
use App\Models\Booking;
use App\Models\BookingEquipmentItem;
use App\Models\Invoice;
use App\Models\LabEquipment;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    // Nilai session_time yang dipakai halaman Tahap 1 => pengali durasi
    private const SESSIONS = [
        'Pagi (08:00-12:00)'  => 1,
        'Siang (13:00-17:00)' => 1,
        'Penuh (08:00-17:00)' => 2,
    ];

    private const TECHNICIAN_FEE = 150000;   // per sesi 4 jam
    private const REAGEN_FEE     = 100000;   // per batch
    private const LIMBAH_FEE     = 50000;    // per sesi 4 jam
    private const DEPOSIT_FEE    = 200000;
    private const DISCOUNT_RATE  = 0.05;
    private const FULL_DAY_SAVING = 50000;

    // GET /bookings/step1/{lab_id}  -> Tahap 1: Instansi & Jadwal
    public function step1($lab_id)
    {
        $lab = Laboratory::with('equipments')
            ->where('is_active', true)
            ->findOrFail($lab_id);

        return view('client.booking.step1', compact('lab'));
    }

    // GET /bookings/step2/{lab_id}  -> Tahap 2: Alat & Add-on
    public function step2($lab_id)
    {
        $lab = Laboratory::with('equipments')
            ->where('is_active', true)
            ->findOrFail($lab_id);

        return view('client.booking.step2', compact('lab'));
    }

    // GET /bookings/step3/{lab_id}  -> Tahap 3: Konfirmasi & Bayar
    public function step3($lab_id)
    {
        $lab = Laboratory::with('equipments')
            ->where('is_active', true)
            ->findOrFail($lab_id);

        $vendors = DB::table('payment_vendors')
            ->where('is_active', 1)
            ->orderBy('vendor_id')
            ->get();

        return view('client.booking.step3', compact('lab', 'vendors'));
    }

    // POST /bookings  -> simpan reservasi + invoice (status awal: pending_review)
    public function store(Request $request)
    {
        $data = json_decode((string) $request->input('payload'), true);
        if (!is_array($data)) {
            return response()->json(['status' => 'error', 'message' => 'Data reservasi tidak valid.'], 422);
        }

        $validator = Validator::make($data, [
            'lab_id'                    => 'required|exists:laboratories,lab_id',
            'reservation_date'          => 'required|date|after_or_equal:today',
            'session_time'              => ['required', Rule::in(array_keys(self::SESSIONS))],
            'total_participants'        => 'required|integer|min:1',
            'technician'                => 'nullable|boolean',
            'reagen'                    => 'nullable|boolean',
            'limbah'                    => 'nullable|boolean',
            'equipments'                => 'nullable|array',
            'equipments.*.equipment_id' => 'required|string',
            'equipments.*.quantity'     => 'required|integer|min:1',
            'vendor_id'                 => 'required|exists:payment_vendors,vendor_id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $request->validate(['proposal' => 'nullable|file|mimes:pdf,doc,docx|max:5120']);

        $user = $request->user();
        $lab  = Laboratory::where('is_active', true)->find($data['lab_id']);
        if (!$lab) {
            return response()->json(['status' => 'error', 'message' => 'Laboratorium tidak ditemukan.'], 404);
        }
        if ((int) $data['total_participants'] > (int) $lab->capacity) {
            return response()->json(['status' => 'error', 'message' => 'Jumlah peserta melebihi kapasitas laboratorium (maks. ' . $lab->capacity . ' orang).'], 422);
        }

        $vendor = DB::table('payment_vendors')->where('vendor_id', $data['vendor_id'])->where('is_active', 1)->first();
        if (!$vendor) {
            return response()->json(['status' => 'error', 'message' => 'Kanal pembayaran tidak tersedia.'], 422);
        }

        $session = $data['session_time'];
        $mult    = self::SESSIONS[$session];

        // Alat tambahan (harga selalu diambil dari database, bukan dari browser)
        $equipmentRows = [];
        $equipmentFee  = 0.0;
        $requested = collect($data['equipments'] ?? []);
        if ($requested->isNotEmpty()) {
            $found = LabEquipment::where('lab_id', $lab->lab_id)
                ->whereIn('equipment_id', $requested->pluck('equipment_id')->all())
                ->get()->keyBy('equipment_id');

            foreach ($requested as $item) {
                $equip = $found->get($item['equipment_id']);
                if (!$equip || $equip->status !== 'available') {
                    return response()->json(['status' => 'error', 'message' => 'Salah satu alat tidak tersedia pada laboratorium ini.'], 422);
                }
                if ((int) $item['quantity'] > (int) $equip->quantity_total) {
                    return response()->json(['status' => 'error', 'message' => 'Jumlah ' . $equip->equipment_name . ' melebihi stok.'], 422);
                }
                $subtotal = (float) $equip->rental_price_per_unit * (int) $item['quantity'];
                $equipmentFee += $subtotal;
                $equipmentRows[] = [
                    'equipment_id' => $equip->equipment_id,
                    'quantity'     => (int) $item['quantity'],
                    'unit_price'   => (float) $equip->rental_price_per_unit,
                    'subtotal'     => $subtotal,
                ];
            }
        }

        // Kalkulasi biaya (sama dengan ringkasan di halaman Tahap 2 & 3)
        $rentFee = (float) $lab->base_price_per_session;
        if ($mult === 2) {
            $rentFee = max($rentFee * 2 - self::FULL_DAY_SAVING, 0);
        }
        $technicianFee = !empty($data['technician']) ? self::TECHNICIAN_FEE * $mult : 0.0;
        $reagenFee     = !empty($data['reagen'])     ? self::REAGEN_FEE : 0.0;
        $limbahFee     = !empty($data['limbah'])     ? self::LIMBAH_FEE * $mult : 0.0;

        $consumableFee = $reagenFee + $limbahFee;
        $subtotal      = $rentFee + $equipmentFee + $consumableFee + $technicianFee;
        $discount      = $subtotal * self::DISCOUNT_RATE;
        $deposit       = (float) self::DEPOSIT_FEE;
        $vendorFee     = (float) $vendor->admin_fee;
        $grandTotal    = $subtotal - $discount + $deposit + $vendorFee;

        try {
            $result = DB::transaction(function () use (
                $request, $data, $lab, $user, $session, $equipmentRows,
                $rentFee, $equipmentFee, $consumableFee, $technicianFee,
                $discount, $deposit, $vendorFee, $grandTotal
            ) {
                // Cek bentrok jadwal (aturan sama dengan kalender Tahap 1)
                $booked = Booking::where('lab_id', $lab->lab_id)
                    ->whereDate('reservation_date', $data['reservation_date'])
                    ->whereIn('status', ['approved', 'paid_confirmed', 'locked_unpaid'])
                    ->lockForUpdate()
                    ->pluck('session_time')->all();

                $full  = 'Penuh (08:00-17:00)';
                $clash = in_array($full, $booked, true)
                    || ($session === $full ? count($booked) > 0 : in_array($session, $booked, true));
                if ($clash) {
                    return ['clash' => true];
                }

                $proposalUrl = null;
                if ($request->hasFile('proposal')) {
                    $proposalUrl = 'storage/' . $request->file('proposal')->store('proposals', 'public');
                }

                $transactionId = 'TRX-' . Str::upper(Str::random(10));
                do {
                    $bookingCode = 'PLP-' . Str::upper(Str::random(6));
                } while (Booking::where('booking_code', $bookingCode)->exists());
                $invoiceNumber = 'INV/PALAPA/' . date('Ym') . '/' . Str::upper(Str::random(5));

                BaseTransaction::create([
                    'transaction_id'     => $transactionId,
                    'transaction_number' => 'TRX/PALAPA/' . date('Ymd') . '/' . Str::upper(Str::random(4)),
                    'user_id'            => $user->user_id,
                    'total_amount'       => $grandTotal,
                ]);

                Booking::create([
                    'booking_id'                => $transactionId,
                    'booking_code'              => $bookingCode,
                    'lab_id'                    => $lab->lab_id,
                    'reservation_date'          => $data['reservation_date'],
                    'session_time'              => $session,
                    'total_participants'        => (int) $data['total_participants'],
                    'proposal_file_url'         => $proposalUrl,
                    'need_technician_assistant' => $technicianFee > 0,
                    'status'                    => 'pending_review',
                ]);

                foreach ($equipmentRows as $row) {
                    BookingEquipmentItem::create([
                        'item_id'        => 'ITM-' . Str::upper(Str::random(10)),
                        'booking_id'     => $transactionId,
                        'equipment_id'   => $row['equipment_id'],
                        'quantity'       => $row['quantity'],
                        'unit_price'     => $row['unit_price'],
                        'subtotal_price' => $row['subtotal'],
                    ]);
                }

                // Diskon dipotongkan dari biaya sewa ruangan; reagen & limbah masuk ke biaya alat/bahan
                $expiredAt = now()->addHours(24);
                Invoice::create([
                    'invoice_id'           => 'INV-' . Str::upper(Str::random(10)),
                    'invoice_number'       => $invoiceNumber,
                    'booking_id'           => $transactionId,
                    'lab_rental_fee'       => $rentFee - $discount,
                    'equipment_fee'        => $equipmentFee + $consumableFee,
                    'technician_fee'       => $technicianFee,
                    'security_deposit_fee' => $deposit,
                    'tax_fee'              => 0,
                    'vendor_admin_fee'     => $vendorFee,
                    'grand_total'          => $grandTotal,
                    'payment_status'       => 'unpaid',
                    'expired_at'           => $expiredAt,
                ]);

                return [
                    'clash'          => false,
                    'booking_code'   => $bookingCode,
                    'invoice_number' => $invoiceNumber,
                    'expired_at'     => $expiredAt->toDateTimeString(),
                ];
            });
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat reservasi: ' . $e->getMessage()], 500);
        }

        if ($result['clash']) {
            return response()->json(['status' => 'error', 'message' => 'Jadwal tersebut baru saja dipesan pengguna lain. Silakan pilih tanggal atau sesi lain.'], 422);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengajuan reservasi berhasil dibuat.',
            'data'    => [
                'booking_code'   => $result['booking_code'],
                'invoice_number' => $result['invoice_number'],
                'grand_total'    => $grandTotal,
                'expired_at'     => $result['expired_at'],
                'vendor_name'    => $vendor->vendor_name,
            ],
        ], 201);
    }
}
