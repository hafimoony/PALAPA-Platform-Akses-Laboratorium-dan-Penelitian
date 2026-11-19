@extends('layouts.app')

@section('title', 'Insiden Kerusakan & Pengembalian Deposit - PALAPA')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h1 class="mb-1" style="font-weight:800; color:#091F5B; font-size:22px;">Pelaporan Insiden & Pengembalian Deposit (T5)</h1>
        <p class="text-muted mb-0" style="font-size:13px;">Catat tiket kerusakan instrumen dan proses pengembalian uang jaminan pasca praktikum.</p>
    </div>
    <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-primary" style="border-radius:10px; font-weight:700; font-size:12px;">
        <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
    </a>
</div>

<div class="row g-4 mb-4">
    <!-- FORM 1: CATAT TIKET INSIDEN KERUSAKAN -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> Catat Insiden Kerusakan Alat
            </div>
            <div class="card-body p-4">
                <form action="{{ route('staff.incidents.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:12px;">Kode Reservasi Terkait</label>
                        <select name="booking_id" class="form-select" required>
                            <option value="">-- Pilih Reservasi --</option>
                            @foreach ($bookings as $b)
                                <option value="{{ $b->booking_id }}">
                                    {{ $b->booking_code }} — {{ $b->laboratory->lab_name ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:12px;">Alat / Instrumen yang Mengalami Kendala</label>
                        <select name="equipment_id" class="form-select">
                            <option value="">-- Tidak Ada / Kerusakan Non-Alat --</option>
                            @foreach ($equipments as $eq)
                                <option value="{{ $eq->equipment_id }}">
                                    {{ $eq->equipment_name }} ({{ $eq->laboratory->lab_name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:12px;">Tingkat Kerusakan</label>
                            <select name="severity" class="form-select" required>
                                <option value="minor">Ringan (Minor)</option>
                                <option value="moderate">Sedang (Moderate)</option>
                                <option value="severe">Berat (Severe / Maintenance)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:12px;">Estimasi Biaya / Potongan (Rp)</label>
                            <input type="number" name="repair_cost_estimate" class="form-control" value="0" min="0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size:12px;">Kronologi & Deskripsi Kerusakan</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Jelaskan kondisi alat atau ruangan setelah digunakan..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fa-solid fa-file-circle-plus me-1"></i> Simpan Tiket Insiden
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- FORM 2: PROSES PENGEMBALIAN DEPOSIT (REFUND) -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fa-solid fa-money-bill-transfer me-1"></i> Proses Pengembalian Deposit Jaminan
            </div>
            <div class="card-body p-4">
                <form action="{{ route('staff.refunds.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:12px;">Pilih Reservasi untuk Refund Deposit</label>
                        <select name="booking_id" class="form-select" required>
                            <option value="">-- Pilih Reservasi --</option>
                            @foreach ($bookings as $b)
                                <option value="{{ $b->booking_id }}">
                                    {{ $b->booking_code }} (Deposit: Rp {{ number_format($b->invoice->security_deposit_fee ?? 0, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:12px;">Potongan Denda Kerusakan (Rp)</label>
                        <input type="number" name="deduction_amount" class="form-control" value="0" min="0" required>
                        <small class="text-muted" style="font-size:11px;">Isi 0 jika seluruh fasilitas dan alat kembali dalam kondisi baik.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size:12px;">Catatan Verifikasi Akhir</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="Contoh: Seluruh alat lengkap dan bersih, deposit dikembalikan 100%."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 py-2" style="background:#091F5B !important; border-color:#091F5B !important;">
                        <i class="fa-solid fa-check-double me-1"></i> Proses Pengembalian Deposit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- TABEL RIWAYAT TIKET INSIDEN -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fa-solid fa-screwdriver-wrench me-1"></i> Daftar Tiket Insiden & Maintenance</span>
        <span class="badge bg-info">{{ $tickets->count() }} Tiket</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID Tiket</th>
                        <th>ID Booking</th>
                        <th>ID Alat</th>
                        <th>Tingkat</th>
                        <th>Deskripsi</th>
                        <th>Estimasi Biaya</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $t)
                        <tr>
                            <td class="fw-bold" style="color:#091F5B;">{{ $t->ticket_id }}</td>
                            <td>{{ $t->booking_id }}</td>
                            <td>{{ $t->equipment_id ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ strtoupper($t->severity) }}</span></td>
                            <td>{{ $t->description }}</td>
                            <td class="fw-bold">Rp {{ number_format($t->repair_cost_estimate ?? 0, 0, ',', '.') }}</td>
                            <td><span class="badge bg-info">{{ strtoupper($t->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada laporan insiden kerusakan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- TABEL RIWAYAT PENGEMBALIAN DEPOSIT -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fa-solid fa-receipt me-1"></i> Riwayat Pengembalian Deposit (Refund)</span>
        <span class="badge bg-info">{{ $refunds->count() }} Transaksi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID Refund</th>
                        <th>ID Booking</th>
                        <th>Deposit Awal</th>
                        <th>Potongan</th>
                        <th>Dana Dikembalikan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($refunds as $r)
                        <tr>
                            <td class="fw-bold" style="color:#091F5B;">{{ $r->refund_id }}</td>
                            <td>{{ $r->booking_id }}</td>
                            <td>Rp {{ number_format($r->original_deposit ?? 0, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($r->deduction_amount ?? 0, 0, ',', '.') }}</td>
                            <td class="fw-bold" style="color:#059669;">Rp {{ number_format($r->refunded_amount ?? 0, 0, ',', '.') }}</td>
                            <td><span class="badge bg-success">{{ strtoupper($r->refund_status ?? 'REFUNDED') }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi pengembalian deposit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
