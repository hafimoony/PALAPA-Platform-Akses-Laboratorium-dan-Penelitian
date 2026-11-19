@extends('layouts.app')
@section('title', 'Insiden Kerusakan & Pengembalian Deposit (T5)')

@section('content')
<div class="row">
    <div class="col-md-6">
        <x-card judul="Catat Tiket Kerusakan / Insiden">
            <form method="POST" action="{{ route('staff.incidents.store') }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Reservasi Terkait</label>
                    <select name="booking_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Kode Booking --</option>
                        @foreach ($bookings as $b)
                            <option value="{{ $b->booking_id }}">{{ $b->booking_code }} - {{ $b->laboratory->lab_name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Alat Rusak (Opsional - Otomatis Ubah Status ke Maintenance)</label>
                    <select name="equipment_id" class="form-select form-select-sm">
                        <option value="">-- Tidak Ada Alat Spesifik --</option>
                        @foreach ($equipments as $eq)
                            <option value="{{ $eq->equipment_id }}">{{ $eq->equipment_name }} ({{ $eq->laboratory->lab_name ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Tingkat Kerusakan</label>
                        <select name="damage_severity" class="form-select form-select-sm">
                            <option value="minor">Ringan (Minor)</option>
                            <option value="moderate">Sedang (Moderate)</option>
                            <option value="severe">Berat (Severe)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Estimasi Biaya Perbaikan (Rp)</label>
                        <input type="number" name="repair_cost_estimate" value="0" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Kronologi Kejadian</label>
                    <textarea name="chronology_description" rows="2" class="form-control form-control-sm" required></textarea>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="deduct_from_deposit" value="1" class="form-check-input" id="deduct" checked>
                    <label class="form-check-label" for="deduct">Potong dari Uang Deposit Penyewa</label>
                </div>
                <button type="submit" class="btn btn-danger btn-sm">Simpan Tiket Insiden</button>
            </form>
        </x-card>
    </div>

    <div class="col-md-6">
        <x-card judul="Proses Pengembalian Deposit (Refund)">
            <form method="POST" action="{{ route('staff.refunds.store') }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Reservasi Selesai</label>
                    <select name="booking_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Kode Booking --</option>
                        @foreach ($bookings as $b)
                            <option value="{{ $b->booking_id }}">{{ $b->booking_code }} (Deposit: Rp {{ number_format($b->invoice->security_deposit_fee ?? 0, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor Rekening / E-Wallet Tujuan Refund</label>
                    <input type="text" name="refund_account_number" placeholder="Contoh: BCA 1234567890" class="form-control form-control-sm" required>
                </div>
                <button type="submit" class="btn btn-success btn-sm">Proses Pengembalian Deposit</button>
            </form>

            <hr>
            <h6>Riwayat Refund Deposit</h6>
            <table class="table table-sm table-bordered">
                <thead><tr><th>ID Refund</th><th>Potongan</th><th>Dikembalikan</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($refunds as $r)
                        <tr>
                            <td><code>{{ $r->refund_id }}</code></td>
                            <td>Rp {{ number_format($r->deduction_amount, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($r->final_refund_amount, 0, ',', '.') }}</strong></td>
                            <td><span class="badge bg-success">{{ $r->refund_status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada data refund.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>
@endsection
